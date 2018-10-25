<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class UploadImage
 * @package App\Models
 * @version October 23, 2018, 6:44 pm UTC
 *
 * @property integer id
 * @property string app_name
 * @property string version
 * @property string domain_name
 * @property string base64_img
 * @property string file_name
 * @property string owner
 * @property string uploaded_img
 * @property date published_date
 */
class UploadImage extends Model
{
    use SoftDeletes;

    public $table = 'uploadImages';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
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
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'app_name' => 'string',
        'version' => 'string',
        'domain_name' => 'string',
        'base64_img' => 'string',
        'file_name' => 'string',
        'owner' => 'string',
        'uploaded_img' => 'string',
        'published_date' => 'date'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    
}
