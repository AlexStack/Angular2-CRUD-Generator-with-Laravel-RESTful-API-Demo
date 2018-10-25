<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateStockAPIRequest;
use App\Http\Requests\API\UpdateStockAPIRequest;
use App\Models\Stock;
use App\Repositories\StockRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

/**
 * Class StockController
 * @package App\Http\Controllers\API
 */

class StockAPIController extends AppBaseController
{
    /** @var  StockRepository */
    private $stockRepository;

    public function __construct(StockRepository $stockRepo)
    {
        $this->stockRepository = $stockRepo;
    }

    /**
     * Display a listing of the Stock.
     * GET|HEAD /stocks
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->stockRepository->pushCriteria(new RequestCriteria($request));
        $this->stockRepository->pushCriteria(new LimitOffsetCriteria($request));
        $stocks = $this->stockRepository->all();

        return $this->sendResponse($stocks->toArray(), 'Stocks retrieved successfully');
    }

    /**
     * Store a newly created Stock in storage.
     * POST /stocks
     *
     * @param CreateStockAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateStockAPIRequest $request)
    {
        $input = $request->all();

        $stocks = $this->stockRepository->create($input);

        return $this->sendResponse($stocks->toArray(), 'Stock saved successfully');
    }

    /**
     * Display the specified Stock.
     * GET|HEAD /stocks/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var Stock $stock */
        $stock = $this->stockRepository->findWithoutFail($id);

        if (empty($stock)) {
            return $this->sendError('Stock not found');
        }

        return $this->sendResponse($stock->toArray(), 'Stock retrieved successfully');
    }

    /**
     * Update the specified Stock in storage.
     * PUT/PATCH /stocks/{id}
     *
     * @param  int $id
     * @param UpdateStockAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateStockAPIRequest $request)
    {
        $input = $request->all();

        /** @var Stock $stock */
        $stock = $this->stockRepository->findWithoutFail($id);

        if (empty($stock)) {
            return $this->sendError('Stock not found');
        }

        $stock = $this->stockRepository->update($input, $id);

        return $this->sendResponse($stock->toArray(), 'Stock updated successfully');
    }

    /**
     * Remove the specified Stock from storage.
     * DELETE /stocks/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var Stock $stock */
        $stock = $this->stockRepository->findWithoutFail($id);

        if (empty($stock)) {
            return $this->sendError('Stock not found');
        }

        $stock->delete();

        return $this->sendResponse($id, 'Stock deleted successfully');
    }
}
