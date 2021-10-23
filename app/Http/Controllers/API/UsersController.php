<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\AccountVerificationRequests;
use App\Services\UsersService;
use Illuminate\Http\Request;
use App\Http\Helpers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Redirect;

class UsersController extends Controller
{
    protected $User;

    protected $helpers;

    public function __construct(User $User, Helpers $helpers)
    {
        $this->User    = $User;
        $this->helpers = $helpers;
    }

    public function index(){

        $data = $this->User->all();

        return $this->helpers->apiArrayResponseBuilder(200, 'success', $data);
    }

    public function create(Request $request){
        $email = substr(filter_var( $request->post('phone'), FILTER_SANITIZE_NUMBER_INT), 3);

        $email = $email . '@imes.pro';

        $number = mt_rand(1, 4294967294);
        $password = Hash::make($request->post('password'));

        // create a user
        $user = User::create([
            'name' => $request->post('name'),
            'phone' => $request->post('phone'),
            'email' => $email,
            'username' => $email,
            'password' => $password,
            'messaging_token' => 'eUpQSLg0fkqLqK8o7T5bD4:APA91bGfNkJ5cr8DXcLubsBlqBz7fSgz_BogwAC5muytt8jOF4VEk6_Vj9D_NMff0owflTvA9TFnEV-DneQJeUGshLktOjC2PUFsmSS4Gz_qTU7ycUh8Fbxi28i0h8pa28fL3jiuJ2g5'
        ]);

        $user->save();

        return Redirect::to('/clients');
    }

    public function createName($name){
        $number = mt_rand(1, 4294967294);
        $email = $number . '@imes.pro';
        $password = Hash::make($name);

        // create only name for users
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'username' => $email,
            'password' => $password,
            'messaging_token' => 'eUpQSLg0fkqLqK8o7T5bD4:APA91bGfNkJ5cr8DXcLubsBlqBz7fSgz_BogwAC5muytt8jOF4VEk6_Vj9D_NMff0owflTvA9TFnEV-DneQJeUGshLktOjC2PUFsmSS4Gz_qTU7ycUh8Fbxi28i0h8pa28fL3jiuJ2g5'
        ]);

        $user->save();

        $data = $this->User->all();

        return $this->helpers->apiArrayResponseBuilder(200, 'success', $data);
    }

    protected function generateUserId( $id) {
        return 100000 + (int)$id;
    }

    public function show($id){

        $data = $this->User->where('id',$id)->first();

        if( count($data) > 0){

            return $this->helpers->apiArrayResponseBuilder(200, 'success', $data);
        }

        $this->helpers->apiArrayResponseBuilder(400, 'bad request', ['error' => 'invalid key']);

    }

    public function store(Request $request){

        $arr = $request->all();

        while ( $data = current($arr)) {
            $this->User->{key($arr)} = $data;
            next($arr);
        }

        $validation = Validator::make($request->all(), $this->User->rules);

        if( $validation->passes() ){
            $this->User->save();
            return $this->helpers->apiArrayResponseBuilder(201, 'created', ['id' => $this->User->id]);
        }else{
            return $this->helpers->apiArrayResponseBuilder(400, 'fail', $validation->errors() );
        }

    }

    public function update($id, Request $request){

        $status = $this->User->where('id',$id)->update($data);

        if( $status ){

            return $this->helpers->apiArrayResponseBuilder(200, 'success', 'Data has been updated successfully.');

        }else{

            return $this->helpers->apiArrayResponseBuilder(400, 'bad request', 'Error, data failed to update.');

        }
    }

    public function delete($id){

        $this->User->where('id', $id)->delete();

        $request = AccountVerificationRequests::where( 'user_id', $id);
        $request->delete();

        return $this->helpers->apiArrayResponseBuilder(200, 'success', 'Data has been deleted successfully.');
    }

    public function destroy($id){

        $this->User->where('id', $id)->delete();
        //return $this->helpers->apiArrayResponseBuilder(200, 'success', 'Data has been deleted successfully.');
    }

    public function block($id){

        $user = User::find($id);
        $user->is_activated = 0;
        $user->save();
        //$user->delete();
    }

    public function unblock($id){

        $user = User::find($id);
        $user->is_activated = 1;
        $user->save();
    }

    public function search($query){
        return User::where('name', 'like', '%' . $query . '%')
            ->orWhere('id', 'like', '%' . $query . '%')
            ->orWhere('username', 'like', '%' . $query . '%')
            ->orWhere('surname', 'like', '%' . $query . '%')
            ->orWhere('phone', 'like', '%' . $query . '%')
            ->orWhere('email', 'like', '%' . $query . '%')
            ->get();
    }

    public function balance(Request $request){
        UsersService::setBalance($request->post('id'), $request->post('count'));
    }

}
