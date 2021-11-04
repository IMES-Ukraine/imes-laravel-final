<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model
 */
class ProjectTags extends Model
{
    /**
     * @var string The database table used by the model.
     */
    public $table = 'ulogic_projects_tags';

    /**
     * Tags
     * @return mixed
     */
    public function project_tags()
    {
        return $this->hasOne('App\Models\Tags', 'id', 'tag_id');
    }
}
