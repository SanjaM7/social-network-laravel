@extends('layouts.app')

@section('content')
<div class="container">
    @if (session('status'))
    <div class="alert alert-success" role="alert">
        {{ session('status') }}
    </div>
    @endif
    <h3><b>Welcome to Chatty</b></h3>
    <p>The best social network, like... ever.</p>
</div>
@endsection