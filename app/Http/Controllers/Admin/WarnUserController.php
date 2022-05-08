<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Warn_user;
use Doctrine\DBAL\Query\QueryException;
use Exception;
use Illuminate\Http\Request;

class WarnUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $users = Warn_user::where('user_id', $request->get('id'))->get();
        return ['data' => $users];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        // dd($request->get('id'));

        $warn =  new Warn_user;
        $warn->user_id = $request->get('id');

        if ($warn->save()) {
            return redirect()->back()->with('success', 'Warning sent successfully');
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
     * @param  \App\Models\Warn_user  $warn_user
     * @return \Illuminate\Http\Response
     */
    public function show(Warn_user $warn_user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Warn_user  $warn_user
     * @return \Illuminate\Http\Response
     */
    public function edit(Warn_user $warn_user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Warn_user  $warn_user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Warn_user $warn_user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Warn_user  $warn_user
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        try {
            Warn_user::where('user_id', $request->user_id)->delete();
            return response()->json(['status' => 'success', 'message' => 'Deleted successfully']);
        } catch (QueryException $e) {
            return response()->json(['status' => 'Failed', 'message' => 'Something went wrong']);
        }
    }
}
