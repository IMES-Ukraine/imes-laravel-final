<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

use App\Services\TestService;
use Illuminate\Http\Request;
use App\Http\Helpers;
use Illuminate\Support\Facades\Validator;
use App\Models\QuestionModeration;

class ModerationController extends Controller
{
    protected $QuestionModeration;

    protected $helpers;

    public function __construct(QuestionModeration $QuestionModeration, Helpers $helpers)
    {
        $this->QuestionModeration    = $QuestionModeration;
        $this->helpers          = $helpers;
    }

    public function index(){

        //$data = $this->QuestionModeration->all()->toArray();
        $data = QuestionModeration::with('question')->get();
        $data = json_decode($data->toJSON());

        return $this->helpers->apiArrayResponseBuilder(200, 'success', $data);
    }

    /**
     * @param $status
     * @param $questionId
     * @return \Illuminate\Http\JsonResponse
     */
    public function review( $status, $questionId ) {

        return $this->helpers
            ->apiArrayResponseBuilder(200, 'success', []);
    }

    /**
     * @param $test_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function test( $test_id )
    {
        $results = $this->QuestionModeration
            ->where('question_id', $test_id)
            ->with('user')
            ->paginate(15);

        $data = json_decode($results->toJSON());

        return $this->helpers
            ->apiArrayResponseBuilder(200, 'success', $data);
    }

    public function show($id){

        $data = $this->QuestionModeration
            ->where('id',$id)->first();

        if( count($data) > 0){

            return $this->helpers
                ->apiArrayResponseBuilder(
                    200,
                    'success',
                    $data);

        }

        $this->helpers
            ->apiArrayResponseBuilder(
                400,
                'bad request',
                [
                    'error' => 'invalid key']);

    }

    public function store(Request $request){

        $arr = $request->all();

        while ( $data = current($arr)) {
            $this->QuestionModeration->{key($arr)} = $data;
            next($arr);
        }

        $validation = Validator::make($request->all(), $this->QuestionModeration->rules);

        if( $validation->passes() ){

            $this->QuestionModeration->save();

            return $this->helpers
                ->apiArrayResponseBuilder(
                    201,
                    'created',
                    [
                        'id' => $this->QuestionModeration->id]);
        } else {
            return $this->helpers
                ->apiArrayResponseBuilder(
                    400,
                    'fail',
                    $validation->errors());
        }

    }

    public function update($id, Request $request){

        //$status = $this->QuestionModeration->where('id',$id)->update($data);

        if( $status = 0 ){

            return $this->helpers
                ->apiArrayResponseBuilder(
                    200,
                    'success',
                    'Data has been updated successfully.');

        }else{

            return $this->helpers
                ->apiArrayResponseBuilder(
                    400,
                    'bad request',
                    'Error, data failed to update.');

        }
    }

    public function delete($id){

        $this->QuestionModeration->where('id',$id)->delete();

        return $this->helpers
            ->apiArrayResponseBuilder(200, 'success', 'Data has been deleted successfully.');
    }

    public function destroy($id){

        $this->QuestionModeration->where('id',$id)->delete();

        return $this->helpers
            ->apiArrayResponseBuilder(
                200,
                'success',
                'Data has been deleted successfully.');
    }
}
