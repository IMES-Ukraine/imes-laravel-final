<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Tags Model
 */
class Tags extends Model
{
     /**
     * @var string The database table used by the model.
     */
    public $table = 'tags';

    /**
     * @var array Fillable fields
     */
    public $fillable = [
        'name',
        'slug',
    ];
}
