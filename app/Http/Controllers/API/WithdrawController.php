<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Http\Helpers;
use Illuminate\Support\Facades\Validator;
use Auth;
use App\Models\Withdraw;

class WithdrawController extends Controller
{
    protected $Withdraw;

    protected $helpers;

    public function __construct(Withdraw $Withdraw, Helpers $helpers)
    {
        $this->Withdraw    = $Withdraw;
        $this->helpers          = $helpers;
    }

    public function index(){

        //$apiUser = Auth::getUser();
        $apiUser = Auth::user();

        $countOnPage = 15;//get('count', 15);

        //$data = $this->Withdraw->all()->orderBy('created_at', 'desc')->toArray();
        $data = Withdraw::where('user_id', $apiUser->id)->orderBy('created_at', 'desc')
            ->paginate($countOnPage);
        $data = json_decode($data->toJSON());

        return $this->helpers->apiArrayResponseBuilder(200, 'success', $data);
    }

    public function show($id){

        $data = $this->Withdraw->where('id',$id)->first();

        if( count($data) > 0){

            return $this->helpers->apiArrayResponseBuilder(200, 'success', $data);

        }

        $this->helpers->apiArrayResponseBuilder(400, 'bad request', ['error' => 'invalid key']);

    }

    public function store(Request $request){

        $arr = $request->all();

        while ( $data = current($arr)) {
            $this->Withdraw->{key($arr)} = $data;
            next($arr);
        }

        $validation = Validator::make($request->all(), $this->Withdraw->rules);

        if( $validation->passes() ){
            $this->Withdraw->save();
            return $this->helpers->apiArrayResponseBuilder(201, 'created', ['id' => $this->Withdraw->id]);
        }else{
            return $this->helpers->apiArrayResponseBuilder(400, 'fail', $validation->errors() );
        }

    }

    public function update($id, Request $request){

        $status = $this->Withdraw->where('id',$id)->update($data);

        if( $status ){

            return $this->helpers->apiArrayResponseBuilder(200, 'success', 'Data has been updated successfully.');

        }else{

            return $this->helpers->apiArrayResponseBuilder(400, 'bad request', 'Error, data failed to update.');

        }
    }

    public function delete($id){

        $this->Withdraw->where('id',$id)->delete();

        return $this->helpers->apiArrayResponseBuilder(200, 'success', 'Data has been deleted successfully.');
    }

    public function destroy($id){

        $this->Withdraw->where('id',$id)->delete();

        return $this->helpers->apiArrayResponseBuilder(200, 'success', 'Data has been deleted successfully.');
    }

}
