<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateLocationAPIRequest;
use App\Http\Requests\API\UpdateLocationAPIRequest;
use App\Models\Location;
use App\Repositories\LocationRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

/**
 * Class LocationController
 * @package App\Http\Controllers\API
 */

class LocationAPIController extends AppBaseController
{
    /** @var  LocationRepository */
    private $locationRepository;

    public function __construct(LocationRepository $locationRepo)
    {
        $this->locationRepository = $locationRepo;
    }

    /**
     * Display a listing of the Location.
     * GET|HEAD /locations
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->locationRepository->pushCriteria(new RequestCriteria($request));
        $this->locationRepository->pushCriteria(new LimitOffsetCriteria($request));
        $locations = $this->locationRepository->all();

        return $this->sendResponse($locations->toArray(), 'Locations retrieved successfully');
    }

    /**
     * Store a newly created Location in storage.
     * POST /locations
     *
     * @param CreateLocationAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateLocationAPIRequest $request)
    {
        $input = $request->all();

        $locations = $this->locationRepository->create($input);

        return $this->sendResponse($locations->toArray(), 'Location saved successfully');
    }

    /**
     * Display the specified Location.
     * GET|HEAD /locations/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var Location $location */
        $location = $this->locationRepository->findWithoutFail($id);

        if (empty($location)) {
            return $this->sendError('Location not found');
        }

        return $this->sendResponse($location->toArray(), 'Location retrieved successfully');
    }

    /**
     * Update the specified Location in storage.
     * PUT/PATCH /locations/{id}
     *
     * @param  int $id
     * @param UpdateLocationAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateLocationAPIRequest $request)
    {
        $input = $request->all();

        /** @var Location $location */
        $location = $this->locationRepository->findWithoutFail($id);

        if (empty($location)) {
            return $this->sendError('Location not found');
        }

        $location = $this->locationRepository->update($input, $id);

        return $this->sendResponse($location->toArray(), 'Location updated successfully');
    }

    /**
     * Remove the specified Location from storage.
     * DELETE /locations/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var Location $location */
        $location = $this->locationRepository->findWithoutFail($id);

        if (empty($location)) {
            return $this->sendError('Location not found');
        }

        $location->delete();

        return $this->sendResponse($id, 'Location deleted successfully');
    }
}
