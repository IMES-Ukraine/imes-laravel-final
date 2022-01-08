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

    /**
     * Before create.
     *
     * @return void
     */
    public function beforeCreate()
    {
        $this->setInitialSlug();
    }

    /**
     * Set the initial slug.
     *
     * @return void
     */
    protected function setInitialSlug()
    {
        $this->slug = str_slug($this->name);
    }
}
