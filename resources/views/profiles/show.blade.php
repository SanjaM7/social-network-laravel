@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-lg-6">
            @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
            <br>
            @endif
            <h3>Profile Info</h3>
            <hr class="my-4">
            <div class="row">
                <div class="col-lg-6">
                    <img src="/uploads/{{ $profile->image }}" style='width:200px;'><br>
                </div>
                <div class="col-lg-6">
                    <p><b>First Name: </b>{{ $profile->first_name }}</p>
                    <p><b>Last Name: </b>{{ $profile->last_name }}</p>
                    <p><b>Years: </b>
                        @if (!empty($profile->birth_year))
                        {{ date("Y") - $profile->birth_year }}
                        @endif
                    </p>
                    <p><b>Gender: </b>{{ $profile->gender }}</p>
                    @if($profile->id == Auth::user()->profile->id )
                    <a href="/profiles/{{ $profile->id }}/edit" class="btn btn-primary">Edit Profile</a>
                    @endif

                    @if(Auth::user()->profile->isFriendWith($profile))
                    <p>You and {{ $profile->first_name }} are friends</p>
                    <form action="{{ route('removeFriend', ['id' => $profile->id])  }}" method="POST">
                        <!-- action('FriendController@removeFriend', ['id' => $profile->id]) -->
                        @csrf
                        <button type="submit" name="removeFriend" class="btn btn-danger">REMOVE FROM FRIENDS</button>
                    </form>
                    @elseif(Auth::user()->profile->hasFriendRequestsReceived($profile))
                    <form action="/friends/{{ $profile->id }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" name="acceptFriendRequest" class="btn btn-success">ACCEPT FRIEND REQUEST</button>
                    </form>
                    <br>
                    <form action="{{ route('declineFriendRequest', ['id' => $profile->id]) }}" method="POST">
                        @csrf
                        <button type="submit" name="declineFriendRequest" class="btn btn-danger">DECLINE FRIEND REQUEST</button>
                    </form>
                    @elseif(Auth::user()->profile->hasfriendRequestsSent($profile))
                    <p>Friend request sent</p>
                    <form action="{{ route('withdrawFriendRequest', ['id' => $profile->id]) }}" method="POST">
                        @csrf
                        <button type="submit" name="withdrawFriendRequest" class="btn btn-primary">WITHRAW FRIEND REQUEST</button>
                    </form>
                    @elseif($profile->id != Auth::user()->profile->id)
                    <form action="/friends" method="POST">
                        @csrf
                        <input type="hidden" name="friend_id" value="{{ $profile->id }}" />
                        <button type="submit" name="addFriend" class="btn btn-success">ADD AS FRIEND</button>
                    </form>
                    @endif
                </div>
            </div>
            <br>
            @if ($errors->any())
            @foreach ($errors->all() as $error)
            <div class="alert alert-danger" role="alert">
                {{ $error }}
            </div>
            @endforeach
            @endif
        </div>
        <div class="col-lg-6">
            <h3> {{ $profile->id == Auth::user()->profile->id ? 'Your Friends' :  $profile->first_name . '\'s friends' }}</h3>
            <hr class="my-4">
            @if(!$profile->friends()->count())
            {{ $profile->first_name }} has no friends.
            @else
            @foreach ($profile->friends() as $profile)
            <a href="/profiles/{{ $profile->id }}"> {{ $profile->first_name . " " . $profile->last_name }} </a><br>
            @endforeach
            @endif
        </div>
    </div>
</div>
@endsection