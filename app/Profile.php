<?php

namespace Chatty;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'first_name', 'last_name', 'birth_year', 'image', 'gender'
    ];

    /**
     * Get the user that owns profile.
     */
    public function user()
    {
        return $this->belongsTo('Chatty\User');
    }

    /**
     * where profile_id = Auth::user()->profile->id 
     */
    public function friendsIAdded()
    {
        return $this->belongsToMany('Chatty\Profile', 'friends', 'profile_id', 'friend_id');
    }

    /**
     * where friend_id = Auth::user()->profile->id
     */
    public function friendsThatAddedMe()
    {
        return $this->belongsToMany('Chatty\Profile', 'friends', 'friend_id', 'profile_id');
    }

    /**
     * friends 
     * where profile_id = Auth::user()->profile->id AND accepted = TRUE
     * OR
     * where friend_id = Auth::user()->profile->id AND accepted = TRUE
     */

    public function friends()
    {
        return $this->friendsIAdded()->wherePivot('accepted', true)->get()->
		merge($this->friendsThatAddedMe()->wherePivot('accepted', true)->get());
    }

    public function isFriendWith(Profile $profile)
    {
        return (bool) $this->friends()->where('id', $profile->id)->count();
    }

    public function friendRequests()
    {
        return $this->friendsThatAddedMe()->wherePivot('accepted', false);
    }

    public function friendRequestsReceived()
    {
        return $this->friendsThatAddedMe()->wherePivot('accepted', false)->get();
    }

    public function hasFriendRequestsReceived(Profile $profile)
    {
        return (bool) $this->friendRequestsReceived()->where('id', $profile->id)->count();
    }

    public function friendRequestsSent()
    {
        return $this->friendsIAdded()->wherePivot('accepted', false)->get();
    }

    public function hasfriendRequestsSent(Profile $profile)
    {
        return (bool) $this->friendRequestsSent()->where('id', $profile->id)->count();
    }

    public function addFriend(Profile $profile)
    {
        $this->friendsIAdded()->attach($profile->id);
    }

    public function acceptFriendRequest(Profile $profile)
    {
        $this->friendRequests()
        ->wherePivot('profile_id', $profile->id)
        ->update([
            'accepted' => true
        ]);
    }
    
    public function deleteFriend(Profile $profile)
    {
        $this->friendsIAdded()->detach($profile->id);
        $this->friendsThatAddedMe()->detach($profile->id);
    }

    public function statuses()
    {
        return $this->hasMany('Chatty\Status');
    }

    public function likes()
    {
        return $this->hasMany('Chatty\Like', 'profile_id');
    }

    public function hasLikedStatus(Status $status)
    {
        return (bool) $status->likes->where('profile_id', $this->id)->count();

    }
}
