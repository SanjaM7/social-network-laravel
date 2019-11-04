<?php

namespace Chatty\Http\Controllers\Timeline;

use Illuminate\Http\Request;
use Chatty\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use Chatty\Status;
use Illuminate\Support\MessageBag;

class LikeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request, $status_id, MessageBag $errors)
    {
        $status = Status::find($status_id);
        if(!$status){
            $errors->add('StatusDoesNotExist', 'You can\'t like post that does not exist!');
        }

        $profile = $request->user()->profile;
        if($status->profile->id != $profile->id && !$profile->isFriendWith($status->profile)){
            $errors->add('NotYourFriend', 'You can only like your own or your friend\'s post');
        }

        if($profile->hasLikedStatus($status)){
            $errors->add('StatusAlreadyLiked', 'You have already liked that post!');
        }

        if($errors->any()){
            return Redirect::back()->with('errors', $errors);
        }
  
        $status->likes()->create([
            'profile_id' => $profile->id,
        ]);

        return Redirect::back()->with('status', 'Post liked.');
    }
}
