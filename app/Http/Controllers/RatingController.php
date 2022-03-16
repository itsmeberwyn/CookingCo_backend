<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use Illuminate\Http\Request;

class RatingController extends Controller
{

    public function index(Request $request)
    {
        $rates = Rating::where('post_id', $request->post_id)->get();
        $aveRate = 0;

        foreach ($rates as $rate) {
            $aveRate += $rate->rate;
        }
        return $aveRate / sizeof($rates);
    }

    public function store(Request $request)
    {
        // dd($request->user()->id);
        $rate = new Rating;

        $this->validate($request, [
            'post_id' => ['required'],
            'rate' => ['required', 'max:255'],
        ]);

        $rate->user_id = $request->user()->id;
        $rate->post_id = $request->post_id;
        $rate->rate = $request->rate;

        if ($rate->save()) {
            return ['status' => 'success', 'message' => 'Rate saved successfully'];
        } else {
            return ['status' => 'Failed', 'message' => 'Something went wrong'];
        }
    }
}
