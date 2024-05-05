<?php

namespace App\Http\Controllers\Admin\Stock;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Admin\Stock\StoreRequest;
use App\Services\Admin\Stock\CreateService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CreateController extends BaseController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * 商品登録画面の初期表示
     *
     * @param Request $request
     * @return View
     */
    public function create(Request $request): View
    {
        return view('admin.stock.create');
    }

    /**
     * 商品登録画面の登録処理
     *
     * @param StoreRequest $request
     * @return RedirectResponse
     * @throws \Exception
     */
    public function store(StoreRequest $request): RedirectResponse
    {
        DB::beginTransaction();
        try {
            /** @var CreateService $service */
            $service = app(CreateService::class);
            $service->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
        return redirect(route('admin.stock'));
    }

}
