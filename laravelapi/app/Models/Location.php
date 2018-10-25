<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Location
 * @package App\Models
 * @version October 23, 2018, 6:43 pm UTC
 *
 * @property integer id
 * @property string country
 * @property string city
 * @property string country_code
 * @property string street
 * @property string address
 * @property string postal_code
 * @property string latitude
 * @property string longitude
 */
class Location extends Model
{
    use SoftDeletes;

    public $table = 'locations';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'id',
        'country',
        'city',
        'country_code',
        'street',
        'address',
        'postal_code',
        'latitude',
        'longitude'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'country' => 'string',
        'city' => 'string',
        'country_code' => 'string',
        'street' => 'string',
        'address' => 'string',
        'postal_code' => 'string',
        'latitude' => 'string',
        'longitude' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    
}
