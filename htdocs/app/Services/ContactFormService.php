<?php

namespace App\Services;

use App\Enums\ErrorType;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Repositories\ContactFormRepository;
use App\Repositories\ContactFormImageRepository;
use App\Services\Utils\UploadImage;

class ContactFormService extends Service
{
    /**
     * @var ContactFormRepository
     */
    protected $contactFormRepository;

    /**
     * @var ContactFormImageRepository
     */
    protected $contactFormImageRepository;

    public function __construct(
        Request                    $request,
        ContactFormRepository      $contactFormRepository,
        ContactFormImageRepository $contactFormImageRepository
    )
    {
        parent::__construct($request);
        $this->contactFormRepository = $contactFormRepository;
        $this->contactFormImageRepository = $contactFormImageRepository;
    }

    /**
     * @param int $limit
     * @return Collection|LengthAwarePaginator|array<string>
     */
    public function list(int $limit = 20): Collection|LengthAwarePaginator|array
    {
        return $this->contactFormRepository->findAll($this->request()->search, [
            'with:images' => true,
            'limit' => $limit,
        ]);
    }

    /**
     * @param string $contactFormId
     * @return object|null
     */
    public function find(string $contactFormId): object|null
    {
        return $this->contactFormRepository->findById($contactFormId, []);
    }

    /**
     * @param string|null $contactFormId
     * @return array<int, mixed>
     */
    public function save(string $contactFormId = null): array
    {
        // 画像ファイルを公開ディレクトリへ配置する。
        if ($this->request()->has('imageBase64') && $this->request()->imageBase64 !== null) {

            $file = UploadImage::convertBase64($this->request()->imageBase64);
            $fileName = time() . $this->request()->fileName;

            //s3に画像をアップロード
            $file->storeAs('', $fileName);

            // $target_path = public_path('uploads/');
            // $file->move($target_path, $fileName);
        } else {
            $fileName = "";
        }

        DB::beginTransaction();
        try {

            if ($contactFormId) {
                // 変更

                $contactForm = $this->contactFormRepository->update(
                    $contactFormId,
                    $this->request()->input('your_name'),
                    $this->request()->input('title'),
                    $this->request()->input('email'),
                    $this->request()->input('url'),
                    $this->request()->input('gender'),
                    $this->request()->input('age'),
                    $this->request()->input('contact')
                );

                // お問い合わせ画像テーブルを登録（Delete→Insert）
                if ($fileName !== "") {
                    $contactFormImages = $this->contactFormImageRepository->findAll($contactFormId);
                    foreach ($contactFormImages as $contactFormImage) {
                        $this->contactFormImageRepository->delete($contactFormImage->id);
                    }
                    $this->contactFormImageRepository->store(
                        null,
                        $contactFormId,
                        $fileName
                    );
                }

            } else {
                // 新規登録

                $contactForm = $this->contactFormRepository->store(
                    null,
                    $this->request()->input('your_name'),
                    $this->request()->input('title'),
                    $this->request()->input('email'),
                    $this->request()->input('url'),
                    $this->request()->input('gender'),
                    $this->request()->input('age'),
                    $this->request()->input('contact')
                );

                $id = $contactForm['id'];

                // お問い合わせ画像テーブルを登録（Insert）
                if ($fileName !== "") {
                    $this->contactFormImageRepository->store(
                        null,
                        $id,
                        $fileName
                    );
                }
            }

            DB::commit();

            return [$contactForm, ErrorType::SUCCESS, null];
        } catch (\PDOException $e) {
            DB::rollBack();
            return [false, ErrorType::DATABASE, $e];
        } catch (\Exception $e) {
            DB::rollBack();
            return [false, ErrorType::FATAL, $e];
        }

    }

    /**
     * @param string $id
     * @return array<int, mixed>
     */
    public function delete(string $id): array
    {
        DB::beginTransaction();
        try {
            // お問い合わせ画像テーブルを削除
            $contactFormImages = $this->contactFormImageRepository->findAll($id);
            foreach ($contactFormImages as $contactFormImage) {
                $this->contactFormImageRepository->delete($contactFormImage->id);
            }
            // お問い合わせテーブルを削除
            $contactForm = $this->contactFormRepository->delete($id);

            DB::commit();
            return [$contactForm, ErrorType::SUCCESS, null];
        } catch (\PDOException $e) {
            DB::rollBack();
            return [false, ErrorType::DATABASE, $e];
        } catch (\Exception $e) {
            DB::rollBack();
            return [false, ErrorType::FATAL, $e];
        }
    }
}
