@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row">

        <div class="col-lg-5">

            <div class="jumbotron text-center">
                <h3>PROFILE EDIT</h3>
                <form method="POST" action="/profiles/{{ $profile->id }}" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')
                    <div class="form-group">
                        <input type="text" name="first_name" class="form-control" placeholder="First Name..." value="{{ $profile->first_name }}">
                    </div>
                    <div class="form-group">
                        <input type="text" name="last_name" class="form-control" placeholder="Last Name..." value="{{ $profile->last_name }}">
                    </div>
                    <div class="form-group">
                        <input type="number" name="birth_year" class="form-control" placeholder="Year of Birth..." value="{{ $profile->birth_year }}">
                    </div>
                    <div class="form-group">
                        <input type="file" name="image" class="form-control-file" id="exampleInputFile" aria-describedby="fileHelp">
                        <small id="fileHelp" class="form-text text-muted">Chosen image name is displayed</small>
                    </div>

                    <div class="form-group">

                        <div>Select gender</div>
                        <div class="row center">

                            <div class="form-check col-lg-4">
                                <label class="form-check-label">
                                    <input type="radio" class="form-check-input " name="gender" id="optionsRadios1" value="M" {{ $profile->gender == 'M' ? 'checked' : '' }}>Male
                                </label>
                            </div>
                            <div class="form-check col-lg-4">
                                <label class="form-check-label">
                                    <input type="radio" class="form-check-input" name="gender" id="optionsRadios2" value="F" {{ $profile->gender == 'F' ? 'checked' : '' }}>Female
                                </label>
                            </div>
                            <div class="form-check col-lg-4">
                                <label class="form-check-label">
                                    <input type="radio" class="form-check-input" name="gender" id="optionsRadios2" value="O" {{ $profile->gender == 'O' ? 'checked' : '' }}>Other
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="center">
                        <button type="submit" name="edit" class="btn btn-primary">Edit Profile</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-lg-7">
            @if ($errors->any())
            @foreach ($errors->all() as $error)
            <div class="alert alert-danger" role="alert">
                {{ $error }}
            </div>
            @endforeach
            @endif
        </div>
    </div>
</div>
@endsection