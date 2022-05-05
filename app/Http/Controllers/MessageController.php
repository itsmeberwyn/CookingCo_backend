<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index(Request $request)
    {
        if ($request->to > $request->from) {
            $criteria_id = $request->to . $request->from;
        } else {
            $criteria_id = $request->from . $request->to;
        }

        $messages = Message::where('criteria_id', $criteria_id)->skip($request->get('skip'))->take(9)->orderBy('created_at', 'desc')->get();

        return ['status' => 'success', 'data' => $messages];
    }

    public function checkInbox(Request $request)
    {
        $messageFrom = Message::where('from', $request->from)->orwhere('to', $request->from)->orderBy('created_at', 'desc')->get()->unique('criteria_id');

        return ['status' => 'success', 'data' => $messageFrom];
    }

    public function store(Request $request)
    {
        $message = new Message;

        $this->validate($request, [
            'message' => ['required'],
            'from' => ['required'],
            'to' => ['required'],
        ]);

        $message->message = $request->message;
        $message->from = $request->from;
        $message->to = $request->to;

        if ($request->to > $request->from) {
            $message->criteria_id = $request->to . $request->from;
        } else {
            $message->criteria_id = $request->from . $request->to;
        }

        // dd($message);

        // return $message;

        if ($message->save()) {
            return ['status' => 'success', 'message' => 'message saved successfully'];
        } else {
            return ['status' => 'Failed', 'message' => 'Something went wrong'];
        }
    }
}
