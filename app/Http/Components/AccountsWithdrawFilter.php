<?php
namespace App\Http\Components;

use Carbon\Carbon;
//use Cms\Classes\ComponentBase;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
//use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
//use RainLab\User\Models\User;
use App\Models\User;
//use ULogic\Analytics\Models\Analytics;
use App\Models\Analytics;
//use ULogic\Profile\Models\City;
use App\Models\City;
//use ULogic\Profile\Models\Store;
use App\Models\Store;
//use ULogic\Profile\Models\Withdraw;
use App\Models\Withdraw;
use League\Csv\Writer as CsvWriter;
use League\Csv\Reader as CsvReader;



class AccountsWithdrawFilter extends Controller
{
    public function componentDetails()
    {
        return [
            'name'        => 'Фiльтр',
            'description' => 'Фiльтрування переказiв'
        ];
    }

    public function onRun()
    {
        $this->stores = $this->page['stores'] = Store::all();
        $this->cities = $this->page['cities'] = City::all();
    }

    public function onExport(Request $request)
    {
        $companyId = trim($request->post( 'company_id'));
        $userId = trim($request->post( 'id'));
        $city   = $request->post( 'city');
        $status = $request->post( 'status');
        $point  = $request->post( 'point');

        $startDate = $request->post( 'start_date');
        $endDate   = $request->post( 'end_date');

        if( empty( $startDate)){
            $startDate = Carbon::now()->subDays(30)->format('d.m.y');
        }
        if( empty( $endDate)){
            $endDate   = Carbon::now()->format('d.m.y');
        }

        $withdraw = new Withdraw();
        $user = new User();

        $data = DB::table($withdraw->getTable())
            ->select($withdraw->getTable().'.id as id', $withdraw->getTable().'.total', 'users.username as user_id', $withdraw->getTable().'.comment', $withdraw->getTable().'.status as status')
            ->join('users', 'users.id', '=', $withdraw->getTable().'.user_id');

        if( !empty( $companyId)) $data->whereRaw('users.company_id = ?', [$companyId]);

        if( !empty( $userId)) $data->whereRaw('users.username = ?', [$userId]);


        if( !empty( $city)){

            foreach ( $city as $value) {
                $data = $data->orWhere( $user->getTable().'.city', $value);
            }
        }
        if( !empty( $point)){

            $data->where(function($q) use ($user, $point) {
                foreach ($point as $value) {

                    $q->orWhere( $user->getTable().'.work', $value);

                }
            });
        }
        if( !empty( $status)){

            $data->where(function($q) use ($status) {
                foreach ($status as $value) {

                    $q->orWhere( 'status', $value);
                }
            });
        }

        $data = $data->get();

        $csv = CsvWriter::createFromPath( "result.csv", "w");

        $csv->insertOne(['Cотрудник компании', '№ аккаунта', /*'Торговая точка',*/ 'Город', 'Cумма перевода', 'Тип перевода', 'Комментарий к переводу', 'id перевода']);
        $csv->insertAll($data->map(function ($i)  {
            return (array) $i;
        })->toArray());


        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Description: File Transfer');
        header('Content-Disposition: attachment; filename="Звіт.csv"');

        $reader = CsvReader::createFromPath("result.csv", 'r');
        $reader->output();
        die;
    }

    public function onApplyFilter()
    {

        $this->records = $this->page['records'] = $this->retrieveData();

        return [
            '.search-results' => $this->renderPartial('@withdraws')
        ];
    }

    /**
     * @return mixed
     */
    public function retrieveData(Request $request)
    {
        $companyId = trim($request->post( 'company_id'));
        $userId = trim($request->post( 'id'));
        $city   = $request->post( 'city');
        $status = $request->post( 'status');
        $point  = $request->post( 'point');

        $startDate = $request->post( 'start_date');
        $endDate   = $request->post( 'end_date');

        if( empty( $startDate)){
            $startDate = Carbon::now()->subDays(30)->format('d.m.y');
        }
        if( empty( $endDate)){
            $endDate   = Carbon::now()->format('d.m.y');
        }

        $withdraw = new Withdraw();
        $user = new User();

        $data = DB::table($withdraw->getTable())
            ->select($withdraw->getTable().'.id as id', $withdraw->getTable().'.total', 'users.username as user_id', $withdraw->getTable().'.comment', $withdraw->getTable().'.status as status')
            ->join('users', 'users.id', '=', $withdraw->getTable().'.user_id');

        if( !empty( $companyId)) $data->whereRaw('users.company_id = ?', [$companyId]);

        if( !empty( $userId)) $data->whereRaw('users.username = ?', [$userId]);


        if( !empty( $city)){

            foreach ( $city as $value) {
                $data = $data->orWhere( $user->getTable().'.city', $value);
            }
        }
        if( !empty( $point)){

            $data->where(function($q) use ($user, $point) {
                foreach ($point as $value) {

                    $q->orWhere( $user->getTable().'.work', $value);

                }
            });
        }
        if( !empty( $status)){

            $data->where(function($q) use ($status) {
                foreach ($status as $value) {

                    $q->orWhere( 'status', $value);
                }
            });
        }

        return $data->get();
    }

    public function onConfirm()
    {
        $model = Withdraw::find(post('id'));
        $model->status = Analytics::APPROVED;
        $model->save();

        return Redirect::to('/perekazy');
    }

    public function onDecline()
    {
        $model = Withdraw::find(post('id'));
        $model->status = Analytics::DECLINED;
        $model->save();

        return Redirect::to('/perekazy');
    }

}
