<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ban_user;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BanUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $users = Ban_user::where('user_id', $request->get('id'))->get();
        return ['data' => $users];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {

        $ban = new Ban_user;

        $ban->user_id = $request->id;
        $ban->ban_till = Carbon::now()->addDays($request->banuser)->format('d-m-Y');

        if ($ban->save()) {
            return redirect()->back()->with('success', 'The user has been ban successfully');
        } else {
            return ['status' => 'Failed', 'message' => 'Something went wrong'];
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Ban_user  $ban_user
     * @return \Illuminate\Http\Response
     */
    public function show(Ban_user $ban_user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Ban_user  $ban_user
     * @return \Illuminate\Http\Response
     */
    public function edit(Ban_user $ban_user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Ban_user  $ban_user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Ban_user $ban_user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Ban_user  $ban_user
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ban_user $ban_user)
    {
        //
    }
}
