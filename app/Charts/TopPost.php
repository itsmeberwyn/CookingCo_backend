<?php

declare(strict_types=1);

namespace App\Charts;

use App\Models\Rating;
use Chartisan\PHP\Chartisan;
use ConsoleTVs\Charts\BaseChart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TopPost extends BaseChart
{
    /**
     * Handles the HTTP request for the given chart.
     * It must always return an instance of Chartisan
     * and never a string or an array.
     */
    public function handler(Request $request): Chartisan
    {
        $topUser = DB::table('ratings')
            ->join('posts', 'posts.id',  '=', 'ratings.post_id')
            ->join('users', 'users.id', '=', 'posts.user_id')
            ->selectRaw("ratings.user_id, users.firstname, users.lastname, SUM(rate) as total_rate")
            ->groupBy('users.id')
            ->get();

        $usersName = array();
        $usersRate = array();

        foreach ($topUser as $key => $user) {
            array_push($usersName, $user->firstname . ' ' . $user->lastname);
            array_push($usersRate, $user->total_rate);
        }

        return Chartisan::build()
            ->labels($usersName)
            ->dataset("top_user", $usersRate);
    }
}
