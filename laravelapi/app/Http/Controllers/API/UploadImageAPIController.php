<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateUploadImageAPIRequest;
use App\Http\Requests\API\UpdateUploadImageAPIRequest;
use App\Models\UploadImage;
use App\Repositories\UploadImageRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

/**
 * Class UploadImageController
 * @package App\Http\Controllers\API
 */

class UploadImageAPIController extends AppBaseController
{
    /** @var  UploadImageRepository */
    private $uploadImageRepository;

    public function __construct(UploadImageRepository $uploadImageRepo)
    {
        $this->uploadImageRepository = $uploadImageRepo;
    }

    /**
     * Display a listing of the UploadImage.
     * GET|HEAD /uploadImages
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->uploadImageRepository->pushCriteria(new RequestCriteria($request));
        $this->uploadImageRepository->pushCriteria(new LimitOffsetCriteria($request));
        $uploadImages = $this->uploadImageRepository->all();

        return $this->sendResponse($uploadImages->toArray(), 'Upload Images retrieved successfully');
    }

    /**
     * Store a newly created UploadImage in storage.
     * POST /uploadImages
     *
     * @param CreateUploadImageAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateUploadImageAPIRequest $request)
    {
        $input = $request->all();

        $uploadImages = $this->uploadImageRepository->create($input);

        return $this->sendResponse($uploadImages->toArray(), 'Upload Image saved successfully');
    }

    /**
     * Display the specified UploadImage.
     * GET|HEAD /uploadImages/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var UploadImage $uploadImage */
        $uploadImage = $this->uploadImageRepository->findWithoutFail($id);

        if (empty($uploadImage)) {
            return $this->sendError('Upload Image not found');
        }

        return $this->sendResponse($uploadImage->toArray(), 'Upload Image retrieved successfully');
    }

    /**
     * Update the specified UploadImage in storage.
     * PUT/PATCH /uploadImages/{id}
     *
     * @param  int $id
     * @param UpdateUploadImageAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateUploadImageAPIRequest $request)
    {
        $input = $request->all();

        /** @var UploadImage $uploadImage */
        $uploadImage = $this->uploadImageRepository->findWithoutFail($id);

        if (empty($uploadImage)) {
            return $this->sendError('Upload Image not found');
        }

        $uploadImage = $this->uploadImageRepository->update($input, $id);

        return $this->sendResponse($uploadImage->toArray(), 'UploadImage updated successfully');
    }

    /**
     * Remove the specified UploadImage from storage.
     * DELETE /uploadImages/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var UploadImage $uploadImage */
        $uploadImage = $this->uploadImageRepository->findWithoutFail($id);

        if (empty($uploadImage)) {
            return $this->sendError('Upload Image not found');
        }

        $uploadImage->delete();

        return $this->sendResponse($id, 'Upload Image deleted successfully');
    }
}
