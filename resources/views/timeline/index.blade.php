@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-lg-6 my-1">
            <form action="/statuses" method="POST">
                @csrf
                <div class="form-group">
                    <textarea name="text" class="form-control" placeholder="What's up {{ Auth::user()->profile->first_name }}?" rows="2"></textarea>
                </div>
                <button type="submit" name="postStatus" class="btn btn-primary">Post Status</button><br>
            </form>
            <br>
            @if ($errors->any())
            @foreach ($errors->all() as $error)
            <div class="alert alert-danger" role="alert">
                {{ $error }}
            </div>
            @endforeach
            @endif

            @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
            <br>
            @endif
        </div>

        <div class="col-lg-6 my-1">
            <h3>Statuses</h3>
            <hr class="my-4">

            @if(!$statuses->count())
            <p>There's nothing in your timeline, yet. </p>
            @else
            @foreach($statuses as $status)
            <div>
                <div class="border rounded px-3 pt-3">
                    <a href="/profiles/{{ $status->profile_id }}">
                        {{ $status->profile->first_name . ' ' . $status->profile->last_name }}
                    </a>
                    <div>{{ $status->text }}</div>
                    <div class="row">
                        <div class="col-lg-6 my-1">
                            <small> {{ $status->created_at->diffForHumans() }} </small>
                        </div>
                        <div class="col-lg-2 my-1">
                            @if(!Auth::user()->profile->hasLikedStatus($status))
                            <form action="/statuses/{{ $status->id }}/likes" method="POST">
                                @csrf
                                <span class="form-group">
                                    <button type="submit" name="like" class="btn btn-link p-0">like</button>
                                </span>
                            </form>
                            @endif
                        </div>
                        <div class="col-lg-4 my-1">
                            {{ $status->likes->count() }} {{ str_plural('like', $status->likes->count()) }}
                        </div>
                    </div>
                </div>

                @if($status->replies->count())
                @foreach($status->replies as $reply)
                <div class="offset-1">
                    <div class="border rounded px-3 pt-3">
                        <a href="/profiles/{{ $reply->profile_id }}">
                            {{ $reply->profile->first_name . ' ' . $reply->profile->last_name }}
                        </a>
                        <div>{{ $reply->text }}</div>
                        <div class="row">
                            <div class="col-lg-6 my-1">
                                <small> {{ $reply->created_at->diffForHumans() }} </small>
                            </div>
                            <div class="col-lg-2 my-1">
                                @if(!Auth::user()->profile->hasLikedStatus($reply))
                                <form action="/statuses/{{ $reply->id }}/likes" method="POST">
                                    @csrf
                                    <span class="form-group">
                                        <button type="submit" name="like" class="btn btn-link p-0">like</button>
                                    </span>
                                </form>
                                @endif
                            </div>
                            <div class="col-lg-4 my-1">
                                {{ $reply->likes->count() }} {{ str_plural('like', $reply->likes->count()) }}
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
                @endif

                <div class="{{ $status->replies->count() ? 'offset-1' : ''}}">
                    <form action="/statuses/{{ $status->id }}/replies" method="POST">
                        @csrf
                        <div class="form-group">
                            <textarea name="text" class="form-control" placeholder="Reply to this status" rows="2"></textarea>
                            <div class="pt-2">
                                <button type="submit" name="postReply" class="btn btn-primary">Reply</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            @endforeach
            @endif

            <br>
            {{ $statuses-> render() }}
        </div>
    </div>
</div>
@endsection