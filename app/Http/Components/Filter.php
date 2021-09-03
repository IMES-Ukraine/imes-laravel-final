<?php
namespace App\Http\Components;

//use BackendAuth;
//use Bedard\BlogTags\Models\Tag;
use App\Http\Controllers\Controller;
use App\Models\Tag;
use Carbon\Carbon;
//use Cms\Classes\ComponentBase;
//use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
//use October\Rain\Support\Facades\Flash;
//use RainLab\User\Models\User;
use App\Models\User;
//use ULogic\Analytics\Models\Analytics;
use App\Models\Analytics;
//use RainLab\Blog\Models\Post;
use App\Models\Post;
use League\Csv\Writer as CsvWriter;
use League\Csv\Reader as CsvReader;


class Filter extends Controller
{

    public function componentDetails()
    {
        return [
            'name'        => 'News filter component',
            'description' => 'Filtering news results of project T'
        ];
    }


    public function onRun()
    {
    }

    /**
     * Export search results to csv
     */
    public function onExport(Request $request)
    {

        $csv = CsvWriter::createFromPath("result.csv", "w");

        $tag = $request->post( 'tag');

        $startDate = $request->post( 'start_date');
        $endDate = $request->post( 'end_date');
        if( empty( $startDate)){
            $startDate = Carbon::now()->subDays( 30)->format('d.m.y');
        }
        if( empty( $endDate)){
            $endDate   = Carbon::now()->format('d.m.y');
        }

        $posts = Post::with( 'featured_images')->isPublished();


        if( !empty($tag)){

            $rawTag = Tag::where('name', 'like', '%' . $tag . '%')->first();

            if(isset( $rawTag->id)) {

                $tagId = $rawTag->id;

                $tagId = $rawTag->id;
                $posts = $posts->whereHas( 'tags', function( $query) use ( $tagId){
                    $query->where( 'tag_id', $tagId);
                });

            } else {
                $posts = $posts->whereHas( 'tags', function( $query) {
                    $query->where( 'tag_id', 0);
                });
            }

        }

        $data = $posts->where('published_at', '>=', Carbon::createFromFormat("d.m.y", $startDate)->toDateTimeString())->where('published_at', '<', Carbon::createFromFormat("d.m.y", $endDate)->toDateTimeString())->get()->toArray();

        //echo '<pre>';print_r($data->items); echo '</pre>'; exit;
        //print_r($data); exit;
        //$csv->insertOne(['title', 'slug']);
        //$people = Post::all();
        $csv->insertAll($data);

        /*$header = ['first name', 'last name', 'email'];
        $records = [
            [1, 2, 3],
            ['foo', 'bar', 'baz'],
            ['john', 'doe', 'john.doe@example.com'],
        ];

        //load the CSV document from a string
        $csv = CsvWriter::createFromString();

        //insert the header
        $csv->insertOne($header);

        //insert all the records
        $csv->insertAll($records);

        echo $csv->toString();*/


        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Description: File Transfer');
        header('Content-Disposition: attachment; filename="Звіт.csv"');

        $reader = CsvReader::createFromPath("result.csv", 'r');
        $reader->output();
        die;
    }
    /**
     * Filtering results in Analytics module
     * @return array
     */
    public function onApplyFilter()
    {
        $tag = Request::post( 'tag');

        $startDate = Request::post( 'start_date');
        $endDate = Request::post( 'end_date');
        if( empty( $startDate)){
            $startDate = Carbon::now()->subDays( 30)->format('d.m.y');
        }
        if( empty( $endDate)){
            $endDate   = Carbon::now()->format('d.m.y');
        }

        $posts = Post::with( 'featured_images')/*->isPublished()*/;


        if( !empty($tag)){

            $rawTag = Tag::where('name', 'like', '%' . $tag . '%')->first();

            if(isset( $rawTag->id)) {

                $tagId = $rawTag->id ;

                $tagId = $rawTag->id;
                $posts = $posts->whereHas( 'tags', function( $query) use ( $tagId){
                    $query->where( 'tag_id', $tagId);
                });

            } else {
                $posts = $posts->whereHas( 'tags', function( $query) {
                    $query->where( 'tag_id', 0);
                });
            }

        }


        $data = $posts->where('published_at', '>=', Carbon::createFromFormat("d.m.y", $startDate)->toDateTimeString())->where('published_at', '<', Carbon::createFromFormat("d.m.y", $endDate)->toDateTimeString())->get();


        Carbon::setLocale('ua');
        #TODO Set locale

        $this->posts = $this->page['posts'] = $data;

        return [
            '.main-content__container' => $this->renderPartial('@index')
        ];
    }

    /**
     * Approving reports, increment User balance
     * @return mixed
     */
    public function onConfirm()
    {
        $model = Analytics::find(post('id'));
        $model->status = Analytics::APPROVED;

        $user = User::find($model->user_id);
        $balance = (int) $user->balance;
        $user->balance = $balance + 250;

        $user->save();
        $model->save();

        return Redirect::to('/analityka');
    }

    public function onDecline()
    {
        $model = Analytics::find(post('id'));
        $model->status = Analytics::DECLINED;
        $model->save();

        return Redirect::to('/analityka');
    }
}
