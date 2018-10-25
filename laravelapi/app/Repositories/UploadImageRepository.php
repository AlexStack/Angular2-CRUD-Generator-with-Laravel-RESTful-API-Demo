<?php

namespace App\Repositories;

use App\Models\UploadImage;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class UploadImageRepository
 * @package App\Repositories
 * @version October 23, 2018, 6:44 pm UTC
 *
 * @method UploadImage findWithoutFail($id, $columns = ['*'])
 * @method UploadImage find($id, $columns = ['*'])
 * @method UploadImage first($columns = ['*'])
*/
class UploadImageRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'id',
        'app_name',
        'version',
        'domain_name',
        'base64_img',
        'file_name',
        'owner',
        'uploaded_img',
        'published_date'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return UploadImage::class;
    }
}
