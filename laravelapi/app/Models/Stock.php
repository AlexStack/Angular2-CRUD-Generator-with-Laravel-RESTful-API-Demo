<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Stock
 * @package App\Models
 * @version October 23, 2018, 6:43 pm UTC
 *
 * @property integer id
 * @property string industry
 * @property string stock_name
 * @property string market_cap
 * @property string currency
 * @property string currency_code
 * @property string sector
 */
class Stock extends Model
{
    use SoftDeletes;

    public $table = 'stocks';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'id',
        'industry',
        'stock_name',
        'market_cap',
        'currency',
        'currency_code',
        'sector'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'industry' => 'string',
        'stock_name' => 'string',
        'market_cap' => 'string',
        'currency' => 'string',
        'currency_code' => 'string',
        'sector' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    
}
