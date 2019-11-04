@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-lg-6 my-1">
            <h3>Your Friends</h3>
            <hr class="my-4">
            @if(!$friends->count())
                <p>You have no friends.</p>
            @else
                @foreach ($friends as $friend)
                    <a href="/profiles/{{ $friend->id }}"> {{ $friend->first_name . " " . $friend->last_name }} </a><br>
                @endforeach
            @endif
        </div>

        <div class="col-lg-6 my-1">
            <h3>Friend Requests</h3>
            <hr class="my-4">
            @if(!$friendRequestsReceived->count())
                You have no friend requests.
            @else
                @foreach ($friendRequestsReceived as $friendRequestReceived)
                    <a href="/profiles/{{ $friendRequestReceived->id }}"> {{ $friendRequestReceived->first_name . " " . $friendRequestReceived->last_name }} </a><br>
                @endforeach
            @endif
        </div>
    </div>
</div>
@endsection