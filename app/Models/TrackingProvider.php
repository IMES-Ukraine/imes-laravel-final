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

    public function startReading($articleId)
    {
        $model = new NewsTracking();
        $model->user_id = $this->user->id;
        $model->position = null;
        $model->news_id = $articleId;
        $model->save();
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

            if ($blockCount === 0 ){
                return  true;
            }

            $totalSymbolsCount = 0;
            $totalWordsCount = 0;

            foreach ($content as $contentBlock) {

                $contentSymbolsCount = iconv_strlen($contentBlock->content);
                $wordsArray = explode(' ', $contentBlock->content);

                array_filter($wordsArray);

                $totalWordsCount = $totalWordsCount + count($wordsArray);
                $totalSymbolsCount = $totalSymbolsCount + $contentSymbolsCount;
            }


            $trackStarted = NewsTracking::where('news_id', $articleId)
                ->where('user_id', $this->user->id)
                ->whereNull('position')
                ->orderBy('created_at', 'desc')
                ->first();

            $trackEnded = NewsTracking::where('news_id', $articleId)
                ->where('user_id', $this->user->id)
                ->where('position', $blockCount)
                ->orderBy('created_at', 'desc')
                //->orderBy('position', 'asc')
                ->first();


            if( $trackStarted && $trackEnded ) {

                $startingDate = !empty( $trackStarted ) ? Carbon::parse( $trackStarted->created_at ) : Carbon::now();
                $endingDate = !empty( $trackEnded ) ? Carbon::parse( $trackEnded->created_at ) : Carbon::now();

                $timeDifference = $startingDate->diffInMinutes($endingDate);

                if( $timeDifference == 0)
                {
                    return false;
                }

                $userReadingRate = $totalSymbolsCount/$timeDifference;

                return $userReadingRate <= env('READING_SYMBOLS_PER_MINUTE');

            } else
            {
                return false;
            }

            /*$startingDate = Carbon::parse($starting['created_at']);
            $endingDate   = Carbon::parse($ending['created_at']);*/
        }
    }
}
