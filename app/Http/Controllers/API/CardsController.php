<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Helpers;
use App\Models\Cards;
use App\Models\User;
use App\Models\UserCards;
use App\Services\UsersService;
use Illuminate\Support\Facades\Auth;

class CardsController extends Controller
{
    protected $helpers;

    public function __construct(Helpers $helpers)
    {
        $this->helpers = $helpers;
    }

    public function index($id)
    {

        $apiUser = Auth::user();

        $card = Cards::findOrFail($id);
        $error = 'Not enough points';

        $user = User::find($apiUser->id);

        if ($user->balance == 0) {
            $error = 'User balance is null';
        }

        $balance = $user->balance - $card->cost;

        if ($balance > 0) {
            $model = new UserCards();
            $model->user_id = $apiUser->id;
            $model->card_id = $id;
            $model->is_active = UserCards::USER_CARDS_IS_ACTIVE;
            $model->cost = $card->cost;
            $model->save();

            UsersService::reduceBalance($apiUser->id, $balance);

            $data = UserCards::where('user_id', $apiUser->id)->get()->toArray();

            if (count($data) > 0) {
                return $this->helpers->apiArrayResponseBuilder(200, 'success', $data);
            }
        }

        return $this->helpers->apiArrayResponseBuilder(400, 'low balance', ['error' => $error]);

    }
}
