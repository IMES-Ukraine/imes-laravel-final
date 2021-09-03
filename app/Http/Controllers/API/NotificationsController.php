<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Helpers;
use Illuminate\Support\Facades\Validator;
use Auth;
use App\Models\User;
use App\Models\Notifications;
use App\Models\NotificationsStatus;

class NotificationsController extends Controller
{
    protected $Notifications;

    protected $helpers;

    public function __construct(Notifications $Notifications, Helpers $helpers)
    {
        //parent::__construct();
        $this->Notifications    = $Notifications;
        $this->helpers          = $helpers;
    }

    public function index(){


        //$apiUser = Auth::getUser();
        $apiUser = Auth::user();

        $countOnPage = 15;//get('count', 15);

        $data = Notifications::where('created_at', '>',  $apiUser->created_at)->where('user_id', $apiUser->id)->orWhereHas( 'status', function( $query) use ($apiUser) {
            $query->where( 'user_id', $apiUser->id);
        })
            ->orderBy('id', 'desc')
            ->limit($countOnPage)
            ->offset(0)
            ->get();
            //->paginate($countOnPage);


        return $this->helpers->apiArrayResponseBuilder(200, 'success', $data);
    }

    public function show($id){

        //$data = $this->Notifications->where('id', '=', $id)->get();
        $data = Notifications::where('id', '=', $id)->get();

        if( !empty($data)){

            return $this->helpers->apiArrayResponseBuilder(200, 'success', $data);

        }

        $this->helpers->apiArrayResponseBuilder(400, 'bad request', ['error' => 'invalid key']);

    }

    public function delete($id){

        $this->Notifications->where('id',$id)->delete();

        return $this->helpers->apiArrayResponseBuilder(200, 'success', ['Data has been deleted successfully.']);
    }

    public function destroy($id){

        //$apiUser = Auth::getUser();
        $apiUser = Auth::user();

        $this->Notifications->where('id',$id)->delete();

        NotificationsStatus::where('notification_id', $id)->where('user_id', $apiUser->id)->delete();

        return $this->helpers->apiArrayResponseBuilder(200, 'success', ['Data has been deleted successfully.']);
    }


    public static function getAfterFilters() {return [];}
    public static function getBeforeFilters() {return [];}
    //public static function getMiddleware() {return [];}
    /*public function callAction($method, $parameters=false) {
        return call_user_func_array(array($this, $method), $parameters);
    }*/

}
