<?php
namespace App\Models;


use App\Models\Post;
use Carbon\Carbon;

class TrackingProvider
{
    protected $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function setBlockReaded($articleId, $blockId = NULL)
    {
        $model = new NewsTracking();
        $model->user_id = $this->user->id;
        $model->position = $blockId;
        $model->news_id = $articleId;
        $model->save();
    }

    public function isReadClosely($articleId)
    {
        $article = Post::findOrFail($articleId);

        if ( is_array($content = $article->content)) {

            $blockCount = count($content) - 1;

            $totalSymbolsCount = 0;
            $totalWordsCount = 0;

            foreach ($content as $contentBlock) {

                $contentSymbolsCount = strlen($contentBlock['content']);
                $wordsArray = explode(' ', $contentBlock['content']);

                array_filter($wordsArray);

                $totalWordsCount = $totalWordsCount + count($wordsArray);
                $totalSymbolsCount = $totalSymbolsCount + $contentSymbolsCount;
            }

            $track = NewsTracking::where('news_id', $articleId)
                ->where('user_id', $this->user->id)
                ->orderBy('created_at', 'asc')
                ->skip(0)
                ->take($blockCount)->get();

            $trackStarted = NewsTracking::where('news_id', $articleId)
                ->where('user_id', $this->user->id)
                ->whereNull('position')
                ->orderBy('created_at', 'desc')
                ->limit(1)
                ->get();

            $trackEnded = NewsTracking::where('news_id', $articleId)
                ->where('user_id', $this->user->id)
                ->where('position', $blockCount)
                ->orderBy('created_at', 'desc')
                //->orderBy('position', 'asc')
                ->limit(1)
                ->get();



            $totalReadingTimeSpent = 0;

            if( isset($trackStarted[0]) && isset($trackEnded[0]) ) {

                $startingDate = !empty( $trackStarted[0] ) ? Carbon::parse( $trackStarted[0]['created_at'] ) : Carbon::now();
                $endingDate = !empty( $trackEnded[0] ) ? Carbon::parse( $trackEnded[0]['created_at'] ) : Carbon::now();

                $timeDifference = $startingDate->diffInMinutes($endingDate);

                if( $timeDifference == 0)
                    return false;

                $userReadingTime = $totalWordsCount/$timeDifference;

                return $userReadingTime < 180;

            } else
                return false;

            /*$startingDate = Carbon::parse($starting['created_at']);
            $endingDate   = Carbon::parse($ending['created_at']);*/
        }
    }
}
