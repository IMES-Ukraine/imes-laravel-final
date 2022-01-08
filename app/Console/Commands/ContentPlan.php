<?php

namespace App\Console\Commands;

use App\Models\PostTimes;
use Illuminate\Console\Command;

class ContentPlan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'content:plan';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command make article time';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $times = PostTimes::all();
        $today_date = date("Y-m-d");
        $today_hour = (int)date("H");
        $today_minute = (int)date("i");

        foreach ($times as $value) {
            $hour = (int)date("H", strtotime($value->time));
            $minute = (int)date("i", strtotime($value->time));

            if (($value->date == $today_date && $today_hour == $hour && $today_minute >= $minute) || ($value->date < $today_date)) {
                PostTimes::find($value->id)->delete();
            }
        }
    }
}
