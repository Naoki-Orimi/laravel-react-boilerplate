<?php

namespace App\Services\Batch;

use App\Domain\Repositories\Stock\StockRepository;
use App\Services\BaseService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class StockService extends BaseService
{
    /**
     * @var StockRepository
     */
    protected StockRepository $stockRepository;

    public function __construct(
        Request $request,
        StockRepository $stockRepository
    )
    {
        parent::__construct($request);
        $this->stockRepository = $stockRepository;
    }

    /**
     * @param int $limit
     * @return Collection|LengthAwarePaginator
     */
    public function searchStock(int $limit = 20): Collection|LengthAwarePaginator
    {
        return $this->stockRepository->findAll(
            $this->request()->name,
            [
                'limit' => $limit,
            ]);
    }

}
