<?php
namespace App\Http\Controllers\API;

use Carbon\Carbon;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Http\Helpers;
use Illuminate\Support\Facades\Validator;
use Auth;
use App\Models\Analytics;

class AnalyticsController extends Controller
{
    protected $Analytics;

    protected $helpers;

    public function __construct(Analytics $Analytics, Helpers $helpers)
    {
        $this->Analytics    = $Analytics;
        $this->helpers          = $helpers;
    }

    public function index(){
        //$apiUser = Auth::getUser();
        $apiUser = Auth::user();

        $rawDate = null;//get('date', null);
        $countOnPage = 15;//get('count', 15);

        if ( !empty($rawDate) ) {
            $date = Carbon::createFromFormat('Y-m-d', $rawDate)->format('Y-m-d');
            //TODO add Order
            $data = Analytics::orderBy('created_at', 'desc')->where('user_id', $apiUser->id)->whereBetween('created_at', [$date.' 00:00:00',  $date.' 23:59:59'])->paginate($countOnPage);
        } else {
            $data = Analytics::orderBy('created_at', 'desc')->where('user_id', $apiUser->id)->paginate($countOnPage);
        }


        return $this->helpers->apiArrayResponseBuilder(200, 'success', $data);
    }

    public function show($id){

        $data = $this->Analytics->where('id',$id)->first();

        if( count($data) > 0){

            return $this->helpers->apiArrayResponseBuilder(200, 'success', $data);

        }

        $this->helpers->apiArrayResponseBuilder(400, 'bad request', ['error' => 'invalid key']);

    }

    public function store(Request $request){

        $arr = $request->all();

        try {

            $this->Analytics->photo_id = $arr['photo_id'];
            $this->Analytics->grams = $arr['grams'];
            $this->Analytics->count = $arr['count'];
            $this->Analytics->creation_time = $arr['creation_time'];

        } catch (\InvalidArgumentException $exception) {

        }


        //$apiUser = Auth::getUser();
        $apiUser = Auth::user();

        $this->Analytics->status = Analytics::IN_PROGRESS;

        $this->Analytics->city = $apiUser->city;
        $this->Analytics->user_id = $apiUser->id;

        $validation = Validator::make($request->all(), $this->Analytics->rules);

        if( $validation->passes() ){
            $this->Analytics->save();
            return $this->helpers->apiArrayResponseBuilder(201, 'created', ['id' => $this->Analytics->id]);
        }else{
            return $this->helpers->apiArrayResponseBuilder(400, 'fail', $validation->errors() );
        }

    }

}
