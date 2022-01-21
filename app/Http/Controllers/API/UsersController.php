<?php

namespace App\Http\Controllers\API;

use App\Exports\ExportUserView;
use App\Http\Controllers\Controller;
use App\Models\AccountVerificationRequests;
use App\Models\Notifications;
use App\Models\Passing;
use App\Models\Post;
use App\Models\ProjectResearches;
use App\Models\Projects;
use App\Models\TestQuestions;
use App\Models\UserCards;
use App\Services\ArticleService;
use App\Services\TestService;
use App\Services\UsersService;
use App\Traits\NotificationsHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Helpers;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Maatwebsite\Excel\Facades\Excel;


class UsersController extends Controller
{
    use NotificationsHelper;

    const COUNT_PER_PAGE = 15;

    const STATUS_NOT_PARTICIPATE = 2; //Не участвовали
    const STATUS_PASSED = 1;          // Выполнили активности
    const STATUS_NOT_PASSED = 0;      //Не выполнили активности

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
        $research = ProjectResearches::where('project_id', $project_id)->get();
        $project = Projects::where('id', $project_id)->first();

        $articles_ids = [];
        $test_ids = [];
        foreach ($research as $item) {
            $articles_ids = array_merge($articles_ids, ArticleService::pluckIDArticles($item->id));
            $test_ids = array_merge($test_ids, TestService::pluckIDRootTests($item->id));
        }
        $passed = Passing::totalPassed($articles_ids, $test_ids);
        switch ($status) {
            case self::STATUS_NOT_PARTICIPATE:
                $results = $project->notParticipateUserIds($articles_ids, $test_ids)->paginate(self::COUNT_PER_PAGE);
                break;
            case self::STATUS_PASSED:
                $results = User::whereIn('id', $passed)->paginate(self::COUNT_PER_PAGE);
                break;
            case self::STATUS_NOT_PASSED:
                $results = Passing::with('user')->where(function ($q) use ($articles_ids) {
                    $q->where('entity_type', Post::class)
                        ->whereIn('entity_id', $articles_ids);
                })
                    ->orWhere(function ($q) use ($test_ids) {
                        $q->where('entity_type', TestQuestions::class)
                            ->whereIn('entity_id', $test_ids);
                    })
                    ->groupBy('user_id')
                    ->whereNotIn('user_id', $passed)
                    ->paginate(self::COUNT_PER_PAGE);
                break;
            default:
                $results = [];

        }

        $data = json_decode($results->toJSON());

        return $this->helpers->apiArrayResponseBuilder(200, 'success', $data);
    }

    public
    function passingTestAll($content_id, $status): JsonResponse
    {
        $research = ProjectResearches::where('id', $content_id)->first();
        /** @var Projects $project */
        $project = $research->project;

        $test_ids = TestService::pluckIDRootTests($content_id);

        switch ($status) {
            case self::STATUS_NOT_PARTICIPATE:
                $results = $project->notParticipateUserIds(false, $test_ids)->paginate(self::COUNT_PER_PAGE);
                break;
            case self::STATUS_NOT_PASSED:
                $results = Passing::with('user')
                    ->isEntityNotPassed(TestQuestions::class, $test_ids)
                    ->groupBy('user_id')
                    ->paginate(self::COUNT_PER_PAGE);
                break;
            case self::STATUS_PASSED:
                $results = Passing::with('user')
                    ->isEntityPassed(TestQuestions::class, $test_ids)
                    ->groupBy('user_id')
                    ->paginate(self::COUNT_PER_PAGE);
                break;
            default:
                $results = [];

        }

        $data = json_decode($results->toJSON());

        return $this->helpers->apiArrayResponseBuilder(200, 'success', $data);
    }

    public
    function passingArticleAll($content_id, $status)
    {
        $research = ProjectResearches::where('id', $content_id)->first();
        $project = $research->project;

        $articles_ids = ArticleService::pluckIDArticles($content_id);

        switch ($status) {
            case self::STATUS_NOT_PARTICIPATE:
                $results = $project->notParticipateUserIds($articles_ids, false)->paginate(self::COUNT_PER_PAGE);
                break;
            case self::STATUS_PASSED:
                $results = Passing::with('user')
                    ->isEntityPassed(Post::class, $articles_ids,)
                    ->paginate(self::COUNT_PER_PAGE);
                break;
            case self::STATUS_NOT_PASSED:
                $results = Passing::with('user')
                    ->isEntityNotPassed(Post::class, $articles_ids,)
                    ->paginate(self::COUNT_PER_PAGE);
                break;
        }

        $data = json_decode($results->toJSON());

        return $this->helpers->apiArrayResponseBuilder(200, 'success', $data);
    }

    public
    function passingTest($test_id, $variant)
    {
        $results = Passing::with('user')
            ->with('withdraw')
            ->where('entity_type', 'LIKE', Passing::PASSING_ENTITY_TYPE_TEST)
            ->where('entity_id', $test_id)
            ->where('answer', 'LIKE', '%' . $variant . '%')
            ->groupBy('user_id')
            ->paginate(self::COUNT_PER_PAGE);

        $data = json_decode($results->toJSON());

        return $this->helpers->apiArrayResponseBuilder(200, 'success', $data);
    }

    private
    function validateUser($data, $user = null)
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

    public
    function create(Request $request)
    {
        $phone = filter_var($request->post('phone'), FILTER_SANITIZE_NUMBER_INT);
        $phone = str_replace('+', '', $phone);
        $email = $request->post('email');
        $password = $request->post('password');

        if ($errors = $this->validateUser(['email' => $email, 'phone' => $phone])) {
            return $this->helpers->apiArrayResponseBuilder(201, 'error', ['error' => $errors]);
        }

        // create a user
        /** @var User $user */
        $user = User::createNewUser($phone, $password, $request->post('name'), $email);

        $verificationRequest = new AccountVerificationRequests;
        $verificationRequest->user_id = $user->id;
        $verificationRequest->save();

        return $this->helpers->apiArrayResponseBuilder(200, 'success', $user->getAttributes());
    }


    public
    function show($id)
    {
        $data = $this->user->where('id', $id)->first();
        if (!$data) {
            $data = $this->user->where('basic_information', 'like', '%' . $id . '%')->first();
            if (!$data) {
                return $this->helpers->apiArrayResponseBuilder(200);
            }
        }
        $data = json_decode($data->toJSON());
        return $this->helpers->apiArrayResponseBuilder(200, 'success', $data);
    }

    public
    function store(Request $request)
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
        }
        else {
            return $this->helpers->apiArrayResponseBuilder(400, 'fail', $validation->errors());
        }

    }

    public
    function update($id, Request $request)
    {
        $user = $this->user->find($id);


        $data = $request->input('data');
        $data['phone'] = filter_var($data['phone'], FILTER_SANITIZE_NUMBER_INT);
        $data['phone'] = str_replace('+', '', $data['phone']);

        if (!$data['email']) {
            $data['email'] = $this->helpers->generateUserName($phone);
        }

        if ($errors = $this->validateUser($data, $user)) {
            return $this->helpers->apiArrayResponseBuilder(201, 'error', ['error' => $errors]);
        }


        $status = $user->update($data);

        if ($status) {

            return $this->helpers->apiArrayResponseBuilder(200, 'Дані було успішно оновлено.');

        }
        else {

            return $this->helpers->apiArrayResponseBuilder(400, 'Під час оновлення даних виникла помилка');

        }
    }

    public
    function delete($id)
    {

        $this->user->where('id', $id)->delete();

        $request = AccountVerificationRequests::where('user_id', $id);
        $request->delete();

        return $this->helpers->apiArrayResponseBuilder(200, 'success', 'Data has been deleted successfully.');
    }

    public
    function destroy($id)
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

    public function balance(Request $request)
    {
        $sum = $request->post('count');
        $id = $request->post('id');
        $user = User::where('id', $id)->first();

        $user->setBalance($sum);

    }

    public
    function cards($user_id)
    {
        $data = UserCards::where('user_id', $user_id)->with('cardall')->get()->toArray();

        if (count($data) > 0) {
            return $this->helpers->apiArrayResponseBuilder(200, 'success', $data);
        }

        return $this->helpers->apiArrayResponseBuilder(400, 'bad request', ['error' => 'Empty cards']);

    }

    public
    function exportUsers($project_id)
    {
        return Excel::download(new ExportUserView($project_id), 'users.xlsx');
    }

    public
    function exportUsersPack($project_id, $content_id)
    {
        return Excel::download(new ExportUserView($project_id, $content_id, true, true), 'users.xlsx');
    }

    public
    function exportUsersArticle($project_id, $content_id)
    {
        return Excel::download(new ExportUserView($project_id, $content_id, true), 'users.xlsx');
    }

    public
    function exportUsersTest($project_id, $content_id)
    {
        return Excel::download(new ExportUserView($project_id, $content_id, false, true), 'users.xlsx');
    }

}
