<?php

namespace App\Http\Controllers\API;

use App\Classes\UserBasicInfo;
use App\Classes\UserFinancialInfo;
use App\Classes\UserSpecializedInfo;
use App\Exports\ExportUserView;
use App\Http\Controllers\Controller;
use App\Models\AccountVerificationRequests;
use App\Models\Passing;
use App\Models\ProjectResearches;
use App\Models\UserCards;
use App\Services\ArticleService;
use App\Services\TestService;
use App\Services\UsersService;
use Illuminate\Http\Request;
use App\Http\Helpers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class UsersController extends Controller
{
    const COUNT_PER_PAGE = 15;
    const STATUS_NOT_PARTICIPATE = 2;
    protected $user;

    protected $helpers;

    public function __construct(User $user, Helpers $helpers)
    {
        $this->user = $user;
        $this->helpers = $helpers;
    }

    public function index()
    {
        $users = $this->user->orderBy('id', 'desc')->isVerified()->paginate();
        $data = json_decode($users->toJSON());
        return $this->helpers->apiArrayResponseBuilder(200, 'success', $data);

    }

    public function list()
    {

        $data = $this->user->select('id', 'name')->get()->toArray();

        return $this->helpers->apiArrayResponseBuilder(200, 'success', $data);
    }

    public function passing($project_id, $status)
    {
        $research = ProjectResearches::select('id')->where('project_id', $project_id)->first();
        $articles_ids = ArticleService::pluckIDArticles($research->id);
        $test_ids = TestService::pluckIDArticles($research->id);

        if ($status == self::STATUS_NOT_PARTICIPATE) {
            $results = User::isNotPassed($articles_ids, $test_ids)->paginate(self::COUNT_PER_PAGE);
        } else {
            $results = User::isPassed($articles_ids, $test_ids, $status)->paginate(self::COUNT_PER_PAGE);
        }

        $data = json_decode($results->toJSON());

        return $this->helpers->apiArrayResponseBuilder(200, 'success', $data);
    }

    public function passingTestAll($content_id, $status)
    {
        $test_ids = TestService::pluckIDArticles($content_id);

        if ($status == self::STATUS_NOT_PARTICIPATE) {
            $results = User::leftJoin('ulogic_projects_passing', 'ulogic_projects_passing.user_id', '=', 'users.id')
                ->whereNull('ulogic_projects_passing.user_id')
                ->orWhereRaw('(status = ' . $status . ' AND `entity_type` LIKE "' . Passing::PASSING_ENTITY_TYPE_TEST . '" AND `entity_id` IN(' . implode(",", $test_ids) . '))')
                ->paginate(self::COUNT_PER_PAGE);
        } else {
            $results = Passing::with('user')
                ->with('withdraw')
                ->whereRaw('(status = ' . $status . ' AND `entity_type` LIKE "' . Passing::PASSING_ENTITY_TYPE_TEST . '" AND `entity_id` IN(' . implode(",", $test_ids) . '))')
                ->paginate(self::COUNT_PER_PAGE);
        }

        $data = json_decode($results->toJSON());

        return $this->helpers->apiArrayResponseBuilder(200, 'success', $data);
    }

    public function passingArticleAll($content_id, $status)
    {
        $articles_ids = ArticleService::pluckIDArticles($content_id);

        if ($status == self::STATUS_NOT_PARTICIPATE) {
            $results = User::leftJoin('ulogic_projects_passing', 'ulogic_projects_passing.user_id', '=', 'users.id')
                ->whereNull('ulogic_projects_passing.user_id')
                ->orWhereRaw('(`ulogic_projects_passing`.`entity_type` LIKE "' . Passing::PASSING_ENTITY_TYPE_POST . '" AND `ulogic_projects_passing`.`entity_id` NOT IN(' . implode(",", $articles_ids) . '))')
                ->paginate(self::COUNT_PER_PAGE);
        } else {
            $results = Passing::with('user')
                ->with('withdraw')
                ->whereRaw('(status = ' . $status . ' AND `entity_type` LIKE "' . Passing::PASSING_ENTITY_TYPE_POST . '" AND `entity_id` IN(' . implode(",", $articles_ids) . '))')
                ->paginate(self::COUNT_PER_PAGE);
        }

        $data = json_decode($results->toJSON());

        return $this->helpers->apiArrayResponseBuilder(200, 'success', $data);
    }

    public function passingTest($test_id, $variant)
    {
        $results = Passing::with('user')
            ->with('withdraw')
            ->where('entity_type', 'LIKE', Passing::PASSING_ENTITY_TYPE_TEST)
            ->where('entity_id', $test_id)
            ->where('answer', 'LIKE', '%' . $variant . '%')
            ->paginate(self::COUNT_PER_PAGE);

        $data = json_decode($results->toJSON());

        return $this->helpers->apiArrayResponseBuilder(200, 'success', $data);
    }

    private function validateUser($data, $user = null)
    {
        $errors = '';

//        Проверяем наличие такого телефона. Плюс вначале игнорируем. Смотрим по полю  UserName

        $userPhone = User::findByUsername($this->helpers->generateUserName($data['phone']));
        if ($userPhone) {
            if (!$user || $user->id != $userPhone->id) {
                $errors .= 'Такой телефон уже зарегистрирован; ';
            }
        }

        $userEmail = User::findByEmail($data['email']);
        if ($userEmail) {
            if (!$user || $user->id != $userEmail->id) {
                $errors .= 'Такой email уже зарегистрирован';
            }
        }
        return $errors;
    }

    public function create(Request $request)
    {
        $phone = filter_var($request->post('phone'), FILTER_SANITIZE_NUMBER_INT);
        $phone = str_replace('+', '', $phone);
        $email = $request->post('email');

        if ($errors = $this->validateUser(['email' => $email, 'phone' => $phone])) {
            return $this->helpers->apiArrayResponseBuilder(201, 'error', ['error' => $errors]);
        }

        $password = Hash::make($request->post('password'));


        // create a user
        /** @var User $user */
        $user = User::createNewUser($phone, $password, $request->post('name'), $email);

        $verificationRequest = new AccountVerificationRequests;
        $verificationRequest->user_id = $user->id;
        $verificationRequest->save();

        return $this->helpers->apiArrayResponseBuilder(200, 'success', $user->getAttributes());
    }


    public function createName($name)
    {
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

        $data = $this->user->all()->toArray();

        return $this->helpers->apiArrayResponseBuilder(200, 'success', $data);
    }


    public function show($id)
    {
        $data = $this->user->where('id', $id)->first();
        if (!$data) {
            return $this->helpers->apiArrayResponseBuilder(400, 'bad request', ['error' => 'invalid key']);
        }
        $data = json_decode($data->toJSON());
        return $this->helpers->apiArrayResponseBuilder(200, 'success', $data);
    }

    public function store(Request $request)
    {

        $arr = $request->all();

        while ($data = current($arr)) {
            $this->user->{key($arr)} = $data;
            next($arr);
        }

        $validation = Validator::make($request->all(), $this->user->rules);

        if ($validation->passes()) {
            $this->user->save();
            return $this->helpers->apiArrayResponseBuilder(201, 'created', ['id' => $this->user->id]);
        } else {
            return $this->helpers->apiArrayResponseBuilder(400, 'fail', $validation->errors());
        }

    }

    public function update($id, Request $request)
    {
        $user = $this->user->find($id);



        $data = $request->input('data');
        $data['phone'] = filter_var($data['phone'], FILTER_SANITIZE_NUMBER_INT);
        $data['phone'] = str_replace('+', '', $data['phone']);

        if (!$data['email']){
            $data['email'] = $this->helpers->generateUserName($phone);
        }

        if ($errors = $this->validateUser($data, $user)) {
            return $this->helpers->apiArrayResponseBuilder(201, 'error', ['error' => $errors]);
        }


        $status = $user->update($data);

        if ($status) {

            return $this->helpers->apiArrayResponseBuilder(200, 'Дані було успішно оновлено.');

        } else {

            return $this->helpers->apiArrayResponseBuilder(400, 'Під час оновлення даних виникла помилка');

        }
    }

    public function delete($id)
    {

        $this->user->where('id', $id)->delete();

        $request = AccountVerificationRequests::where('user_id', $id);
        $request->delete();

        return $this->helpers->apiArrayResponseBuilder(200, 'success', 'Data has been deleted successfully.');
    }

    public function destroy($id)
    {
        User::find($id)->user_cards()->delete();
        $this->delete($id);
    }

    public
    function block($id)
    {

        $user = User::find($id);
        $user->is_activated = 0;
        $user->save();
        //$user->delete();
    }

    public
    function unblock($id)
    {

        $user = User::find($id);
        $user->is_activated = 1;
        $user->save();
    }

    public
    function search($query)
    {
        return User::where('name', 'like', '%' . $query . '%')
            ->orWhere('id', 'like', '%' . $query . '%')
            ->orWhere('username', 'like', '%' . $query . '%')
            ->orWhere('surname', 'like', '%' . $query . '%')
            ->orWhere('phone', 'like', '%' . $query . '%')
            ->orWhere('email', 'like', '%' . $query . '%')
            ->get();
    }

    public
    function balance(Request $request)
    {
        UsersService::setBalance($request->post('id'), $request->post('count'));
    }

    public function cards($user_id)
    {
        $data = UserCards::where('user_id', $user_id)->with('card')->get()->toArray();

        if (count($data) > 0) {
            return $this->helpers->apiArrayResponseBuilder(200, 'success', $data);
        }

        return $this->helpers->apiArrayResponseBuilder(400, 'bad request', ['error' => 'Empty cards']);

    }

    public function exportUsers($project_id)
    {
        return Excel::download(new ExportUserView($project_id), 'users.xlsx');
    }

    public function exportUsersArticle($project_id, $content_id)
    {
        return Excel::download(new ExportUserView($project_id, $content_id, true), 'users.xlsx');
    }

    public function exportUsersTest($project_id, $content_id)
    {
        return Excel::download(new ExportUserView($project_id, $content_id, false, true), 'users.xlsx');
    }

}
