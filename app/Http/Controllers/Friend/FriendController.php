<?php

namespace Chatty\Http\Controllers\Friend;

use Chatty\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Chatty\Profile;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\MessageBag;

class FriendController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $friends = $request->user()->profile->friends();
        $friendRequestsReceived = $request->user()->profile->friendRequestsReceived();

        return view('friends.index', [
            'friends' => $friends,
            'friendRequestsReceived' => $friendRequestsReceived
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, MessageBag $errors)
    {
        //ADD AS FRIEND
        $profile = $request->user()->profile;
        $friend = Profile::findOrFail($request->friend_id);

        if($friend->id == $profile->id)
        {
            $errors->add('YourProfile', 'Invalid attempt you can not add yourself as a friend!');
        }

        if($profile->isFriendWith($friend))
        {
            $errors->add('Friends', 'Invalid attempt this person is your friend!');
        }

        if($profile->hasFriendRequestsReceived($friend))
        {
            $errors->add('HaveFriendRequest', 'Invalid attempt you have friend request from this person!');
        }

        if($profile->hasfriendRequestsSent($friend))
        {
            $errors-> add('SentFriendRequest', 'Invalid attempt you have sent friend request to this person!');
        }

        if($errors->any())
        {
            return redirect()
            ->action('Profile\ProfileController@show', ['$profile' => $friend])
            ->with('errors', $errors);
        }

        $profile->addFriend($friend);

        return Redirect::back()->with('status', 'Friend request sent.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id, MessageBag $errors)
    {
        //ACCEPT FRIEND REQUEST
        $profile = $request->user()->profile;
        $friend = Profile::findOrFail($id);

        if(!$profile->hasFriendRequestsReceived($friend))
        {
            $errors->add('NotHaveFriendRequest', 'Invalid attempt you do not have friend request from this person!');
        }

        if($profile->isFriendWith($friend))
        {
            $errors->add('Friends', 'Invalid attempt this person is your friend!');
        }

        if($profile->hasfriendRequestsSent($friend))
        {
            $errors->add('SentFriendRequest', 'Invalid attempt you have sent friend request to this person!');
        }

        if($errors->any())
        {
            return redirect()
            ->action('Profile\ProfileController@show', ['$profile' => $friend])
            ->with('errors', $errors);
        }

        $profile->acceptFriendRequest($friend);

        return Redirect::back()->with('status', 'Friend accepted.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
    }

    public function removeFriend(Request $request, $id, MessageBag $errors)
    {
        //REMOVE FROM FRIENDS
        $profile = $request->user()->profile;
        $friend = Profile::findOrFail($id);

        if(!$profile->isFriendWith($friend))
        {
            $errors->add('NotFriends','Invalid attempt this person is not your friend!');
        }

        if($profile->hasFriendRequestsReceived($friend))
        {
            $errors->add('HaveFriendRequest', 'Invalid attempt you have friend request from this person!');
        }

        if($profile->hasfriendRequestsSent($friend))
        {
            $errors->add('SentFriendRequest', 'Invalid attempt you have sent friend request to this person!');
        }

        if($errors->any())
        {
            return redirect()
            ->action('Profile\ProfileController@show', ['$profile' => $friend])
            ->with('errors', $errors);
        }

        $profile->deleteFriend($friend);

        return Redirect::back()->with('status', 'Removed from friends.');
    }

    public function declineFriendRequest(Request $request, $id, MessageBag $errors)
    {
        //DECLINE FRIEND REQUEST
        $profile = $request->user()->profile;
        $friend = Profile::findOrFail($id);

        if(!$profile->hasFriendRequestsReceived($friend))
        {
            $errors->add('NotHaveFriendRequest', 'Invalid attempt you do not have friend request from this person!');
        }

        if($profile->isFriendWith($friend))
        {
            $errors->add('Friends', 'Invalid attempt this person is already your friend!');
        }

        if($profile->hasfriendRequestsSent($friend))
        {
            $errors->add('SentFriendRequest', 'Invalid attempt you have sent friend request to this person!');
        }

        if($errors->any())
        {
            return redirect()
            ->action('Profile\ProfileController@show', ['$profile' => $friend])
            ->with('errors', $errors);
        }
        
        $profile->deleteFriend($friend);
        return Redirect::back()->with('status', 'Friend request declined.');
    }

    public function withdrawFriendRequest(Request $request, $id, MessageBag $errors)
    {
        //WITHRAW FRIEND REQUEST
        $profile = $request->user()->profile;
        $friend = Profile::findOrFail($id);

        if(!$profile->hasfriendRequestsSent($friend))
        {
            $errors->add('NotSentFriendRequest','Invalid attempt you haven\'t send request to this person!');
        }

        if($profile->isFriendWith($friend))
        {
            $errors->add('Friends', 'Invalid attempt this person is already your friend!');
        }

        if($profile->hasFriendRequestsReceived($friend))
        {
            $errors->add('HaveFriendRequest', 'Invalid attempt you have friend request from this person!');
        }

        if($errors->any())
        {
            return redirect()
            ->action('Profile\ProfileController@show', ['$profile' => $friend])
            ->with('errors', $errors);
        }
        
        $profile->deleteFriend($friend);

        return Redirect::back()->with('status', 'Friend request withdrew.');
    }
}
