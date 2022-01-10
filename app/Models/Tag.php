<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Post;

/**
 * Tag Model
 */
class Tag extends Model
{
    //use \October\Rain\Database\Traits\Validation;

    /**
     * @var string The database table used by the model.
     */
    public $table = 'bedard_blogtags_tags';

    /**
     * @var array Relations
     */
    public $belongsToMany = [
        'posts' => [
            'App\Models\Post',
            'table' => 'bedard_blogtags_post_tag',
            'order' => 'published_at desc'
        ]
    ];

    /**
     * @var array Fillable fields
     */
    public $fillable = [
        'name',
        'slug',
    ];

    /**
     * @var array Validation rules
     */
    public $rules = [
        'name' => 'required|unique:bedard_blogtags_tags'
    ];

    public $customMessages = [
        'name.required' => 'bedard.blogtags::lang.form.name_required',
        'name.unique'   => 'bedard.blogtags::lang.form.name_unique',
        'name.regex'    => 'bedard.blogtags::lang.form.name_invalid',
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

    /**
     * Convert tag names to lower case
     */
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = mb_strtolower($value);
    }

    /**
     * Sets the "url" attribute with a URL to this object
     *
     * @param string                    $pageName
     * @param App\Http\Controllers\Controller    $controller
     */
    public function setUrl($pageName, $controller)
    {
        $params = [
            'id' => $this->id,
            'slug' => $this->slug,
        ];

        return $this->url = $controller->pageUrl($pageName, $params);
    }
}
