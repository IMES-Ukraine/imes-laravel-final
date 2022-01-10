<?php

namespace App\Models;


use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class TrackingProvider
{
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

            $blockCount = count($content) - 1;

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

            $trackEnded = NewsTracking::where('news_id', $article->id)
                ->where('user_id', $this->user->id)
                ->where('position', $blockCount)
                ->orderBy('created_at', 'desc')
                ->first();


            if ($trackStarted && $trackEnded) {

                $startingDate = !empty($trackStarted) ? Carbon::parse($trackStarted->created_at) : Carbon::now();
                $endingDate = !empty($trackEnded) ? Carbon::parse($trackEnded->created_at) : Carbon::now();

                $timeDifference = $startingDate->diffInMinutes($endingDate);

                if ($timeDifference == 0) {
                    return false;
                }
                $userReadingRate = $totalSymbolsCount / $timeDifference;

                //TODO разобраться, почему пропадают параметры из конфига
                $rate = config('params')['maxReadingRate'] ?? 2000;
                return $userReadingRate <= $rate;
            } else {
                return false;
            }

            /*$startingDate = Carbon::parse($starting['created_at']);
            $endingDate   = Carbon::parse($ending['created_at']);*/
        }
        return false;
    }
}
