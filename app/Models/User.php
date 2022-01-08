<?php

namespace App\Models;

use App\Classes\UserBasicInfo;
use App\Classes\UserFinancialInfo;
use App\Classes\UserSpecializedInfo;
use App\Http\Helpers;
use App\Traits\JWT;
use App\Traits\JsonFieldTrait;
use App\Traits\NotificationsHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * App\Models\User
 *
 * @property int $id
 * @property string|null $name
 * @property string $email
 * @property string $password
 * @property string|null $activation_code
 * @property string|null $persist_code
 * @property string|null $reset_password_code
 * @property string|null $permissions
 * @property boolean $is_activated
 * @property string|null $activated_at
 * @property string|null $last_login
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $username
 * @property string|null $surname
 * @property string|null $deleted_at
 * @property string|null $last_seen
 * @property boolean $is_guest
 * @property boolean $is_superuser
 * @property string|null $phone
 * @property string|null $city
 * @property string|null $work
 * @property string|null $messaging_token
 * @property int|null $company_id
 * @property string|null $firebase_token
 * @property boolean $is_verified
 * @property mixed|null $basic_information
 * @property mixed|null $specialized_information
 * @property mixed|null $financial_information
 * @property int $balance
 * @method static \Database\Factories\UserFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereActivatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereActivationCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereBasicInformation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereFinancialInformation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereFirebaseToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereIsActivated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereIsGuest($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereIsSuperuser($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereIsVerified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLastLogin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLastSeen($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereMessagingToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePermissions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePersistCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereResetPasswordCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereSpecializedInformation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereSurname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUsername($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereWork($value)
 */
class User extends Authenticatable implements JWTSubject
{
    use NotificationsHelper;
    use HasFactory, JWT, JsonFieldTrait;

    const USER_IS_VERIFIED_FALSE = 0;
    const USER_IS_VERIFIED_TRUE = 1;

    const ENTYTY_TYPE_TEST = ' выполнение теста ';
    const ENTYTY_TYPE_ARTICLE = ' чтение статьи ';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'username',
        'phone',
        'is_activated',
        'basic_information',
        'specialized_information',
        'financial_information'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'is_activated' => 'boolean',
        'is_guest' => 'boolean',
        'is_superuser' => 'boolean',
        'is_verified' => 'boolean',
        'email_verified_at' => 'datetime',
        'basic_information' => 'array',
        'specialized_information' => 'array',
        'financial_information' => 'array',
    ];


    const TO_HIDE = [
        'messaging_token', 'firebase_token', 'activation_code', 'persist_code', 'reset_password_code', 'is_activated',
        'permissions', 'deleted_at', 'updated_at', 'activated_at',
        'basic_information', 'specialized_information', 'financial_information',
    ];

    public function scopeIsNotPassed($query, $articles_ids, $test_ids)
    {
        if ($articles_ids) {
            $in_articles = Passing::select('user_id')
                ->where('ulogic_projects_passing.entity_type', Post::class)
                ->whereIn('ulogic_projects_passing.entity_id', $articles_ids)
                ->distinct();

            $query->whereNotIn('id', $in_articles);
        }

        if ($test_ids) {
            $in_tests = Passing::select('user_id')
                ->where('ulogic_projects_passing.entity_type', TestQuestions::class)
                ->whereIn('ulogic_projects_passing.entity_id', $test_ids)
                ->distinct();

            $query->whereNotIn('id', $in_tests);
        }
    }

    public function scopeIsPassed($query, $articles_ids, $test_ids, $status)
    {
        if ($articles_ids) {
            $query_article = Passing::select('user_id')
                ->where('ulogic_projects_passing.entity_type', Post::class)
                ->whereIn('ulogic_projects_passing.entity_id', $articles_ids);

            if ($status) $query_article->where('status', $status);

            $in_articles = $query_article->distinct();
            $query->whereIn('id', $in_articles);
        }

        if ($test_ids) {
            $query_test = Passing::select('user_id')
                ->where('ulogic_projects_passing.entity_type', TestQuestions::class)
                ->whereIn('ulogic_projects_passing.entity_id', $test_ids);

            if ($status) $query_test->where('status', $status);

            $in_tests = $query_test->distinct();
            $query->orWhereIn('id', $in_tests);
        }
    }

    public function scopeIsVerified($query)
    {
        $query->where('is_verified', self::USER_IS_VERIFIED_TRUE);
    }

    /**
     * Looks up a user by their email address.
     * @return self|null
     */
    public static function findByEmail($email)
    {
        if (!$email) {
            return null;
        }
        return self::where('email', $email)->first();
    }

    /**
     * Looks up a user by their phone.
     * @return self|null
     */
    public static function findByPhone($phone)
    {
        if (!$phone) {
            return null;
        }
        $phone = str_replace('+', '', $phone);
        return self::where('phone', $phone)->first();
    }

    /**
     * Looks up a user by their username.
     * @return self|null
     */
    public static function findByUsername($username)
    {
        if (!$username) {
            return null;
        }
        return self::where('username', $username)->first();
    }


    public function city_info()
    {
        return $this->hasOne(City::class, 'id', 'city');
    }

    public static function createNewUser($phone, $password = null, $name = null, $email = null)
    {
        $username = (new Helpers())->generateUserName($phone);
        $email = $email ?? $username;
        $passwordHash = Hash::make($password ?? $username);
        $userInfo = new UserBasicInfo([
            'name' => $name ?? $phone,
            'phone' => $phone,
            'email' => $email,
        ]);
//        Log::info('userCreate: ', [$username, $password, $passwordHash]);
        return self::create([
            'phone' => $phone,
            'name' => $name ?? $phone,
            'username' => $username,
            'email' => $email,
            'password' => $passwordHash,
            'is_activated' => true,
            'basic_information' => $userInfo,
            'specialized_information' => new UserSpecializedInfo(),
            'financial_information' => new UserFinancialInfo(),
            'firebase_token' => ''
        ]);
    }

    public function addBalance($sum, $model=null, $entityType=null)
    {
        $this->balance += $sum;
        if ($this->save() ) {
            if ($model) {
                if (get_class($model) === TestQuestions::class) {
                    $this->sendNotificationToUser($this, Notifications::TYPE_REFILL,
                        'Вам начислено ' . $sum . ' баллов за' . self::ENTYTY_TYPE_TEST . $model->title, [],
                        'Вам начислены баллы');
                }
                if (get_class($model) === Post::class) {
                    $this->sendNotificationToUser($this, Notifications::TYPE_REFILL,
                        'Вам начислено ' . $sum . ' баллов за' . self::ENTYTY_TYPE_ARTICLE . $model->title, [],
                        'Вам начислены баллы');
                }
            }
        }
    }


    /**
     * Get the cards of the users
     *
     */
    public function user_cards(): HasMany
    {
        return $this->hasMany(UserCards::class);
    }

}
