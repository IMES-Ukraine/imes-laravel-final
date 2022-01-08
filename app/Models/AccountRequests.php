<?php
namespace App\Models;

use App\Models\Store;
use Illuminate\Database\Eloquent\Model;
use App\Models\City;

/**
 * Model
 */
class AccountRequests extends Model
{
    protected $dates = ['deleted_at'];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'ulogic_registration_requests';

    /**
     * @var array Validation rules
     */
    public $rules = [
        'name' => 'required',
        //'email' => 'required|email|unique:ulogic_registration_requests,email',
        'email' => 'required|email',
        'phone' => 'required',
        'city' => 'required',
        'work' => 'required',
        'company_id' => 'required',
    ];

    public $customMessages = [
        //'email.unique' => 'Користувач з такою поштою вже зробив запит',
    ];

    protected $fillable = ['name', 'email', 'phone', 'city', 'work', 'company_id'];

    /*public $hasOne = [
        'userCity' => [City::class, 'key' => 'id', 'otherKey' => 'city'],
        'userWork' => [Store::class, 'key' => 'id', 'otherKey' => 'work']
    ];*/

    public function userCity() {
        return $this->hasOne(City::class, 'id', 'city');
    }

    public function userWork() {
        
        return $this->hasOne(Store::class, 'id', 'work');
    }
}
