<?php

namespace Chatty\Http\Controllers\Timeline;

use Illuminate\Http\Request;
use Chatty\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use Chatty\Status;
use Illuminate\Support\MessageBag;

class ReplyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function store(Request $request, $status_id, MessageBag $errors)
    {
        $request->validate([
            'text' => ['required', 'max:140']
        ]);

        $status = Status::find($status_id);

        if(!$status){
            $errors->add('StatusDoesNotExist', 'You can\'t reply to post that does not exist!');
        }

        if($status->parent_id){
            $errors->add('CanNotReplyOnReply', 'You can only reply to statuses!');
        }

        $profile = $request->user()->profile;
        if($status->profile->id != $profile->id && !$profile->isFriendWith($status->profile)){
            $errors->add('NotYourFriend', 'You can only reply to your own or your friend\'s post!');
        }

        if($errors->any()){
            return Redirect::back()->with('errors', $errors);
        }
        
        $profile = $request->user()->profile;
        $profile->statuses()->create([
            'text' => $request->input('text'),
            'parent_id' => $status_id
        ]);

        return Redirect::back()->with('status', 'Reply posted.');
    }
}
