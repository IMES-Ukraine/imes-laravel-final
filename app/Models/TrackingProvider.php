<?php

namespace App\Models;


use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class TrackingProvider
{

    const SECONDS_IN_MINUTE = 60;
    const SYMBOLS_PER_MINUTE = 2000;

    protected $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function startReading($articleId)
    {
        $model = NewsTracking::updateOrCreate([
            'user_id' => $this->user->id,
            'position' => null,
            'news_id' => $articleId
        ], [
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }

    public function setBlockReaded($articleId, $blockId = NULL)
    {
        $model = NewsTracking::updateOrCreate([
            'user_id' => $this->user->id,
            'position' => $blockId,
            'news_id' => $articleId
        ], [
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }

    public function isReadClosely($article)
    {
        if (is_array($content = $article->content)) {

            //What for?
            //$blockCount = count($content) - 1;
            $blockCount = count($content);
            if ($blockCount === 0) {
                return true;
            }

            $totalSymbolsCount = 0;

            foreach ($content as $contentBlock) {
                $contentSymbolsCount = iconv_strlen($contentBlock->content);
                $totalSymbolsCount += $contentSymbolsCount;
            }

            $trackStarted = NewsTracking::where('news_id', $article->id)
                ->where('user_id', $this->user->id)
                ->whereNull('position')
                ->orderBy('created_at', 'desc')
                ->first();

            //decrement for sql
            $blockCount -= 1;
            $trackEnded = NewsTracking::where('news_id', $article->id)
                ->where('user_id', $this->user->id)
                ->where('position', $blockCount)
                ->orderBy('created_at', 'desc')
                ->first();
            if ($trackStarted && $trackEnded) {

                $startingDate = !empty($trackStarted) ? Carbon::parse($trackStarted->created_at) : Carbon::now();
                $endingDate = !empty($trackEnded) ? Carbon::parse($trackEnded->created_at) : Carbon::now();


                $timeDifference = $startingDate->diffInSeconds($endingDate);
                if ($timeDifference == 0) {
                    return false;
                }

                $rate = config('params.maxReadingRate') ?? self::SYMBOLS_PER_MINUTE;

                $minimumReading = round(($totalSymbolsCount/$rate)*self::SECONDS_IN_MINUTE);
                //return $userReadingRate <= $rate;
                return $timeDifference >= $minimumReading;
            } else {
                return false;
            }
        }
        return false;
    }
}
