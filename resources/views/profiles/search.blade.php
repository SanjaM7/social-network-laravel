@extends('layouts.app')
@section('content')
<div class="container">
    <h3>Search</h3>
    <hr class="my-4">
    <h4><b>You have searched for: </b> "{{ Request::input('query') }}" </h4>
    @if(!$profiles->count())
    <p>There are no results that match your search</p>
    @else
    @foreach($profiles as $profile)
    <a href="/profiles/{{ $profile->id }}">{{ $profile->first_name . " " . $profile->last_name }}</a> <br>
    @endforeach
    @endif
</div>
@endsection