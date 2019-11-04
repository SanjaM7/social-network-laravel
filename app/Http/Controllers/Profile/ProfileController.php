<?php

namespace Chatty\Http\Controllers\Profile;

use Chatty\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Chatty\Profile;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
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
        $query = $request->input('query');

        if (!$query) {
            return redirect()->route('home');
        }

        $profiles = Profile::where(
            DB::raw("CONCAT(first_name, ' ', last_name)"),
            'LIKE',
            "%{$query}%"
        )->get();

        return view('profiles.search', [
            'profiles' => $profiles
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Profile $profile)
    {
        return view('profiles.show', [
            'profile' => $profile
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $profile = $request->user()->profile;
        return view('profiles.edit', [
            'profile' => $profile
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $profile = $request->user()->profile;

        $validatedData = $request->validate([
            'first_name' => ['sometimes', 'nullable', 'alpha', 'max:50'],
            'last_name' => ['sometimes', 'nullable', 'alpha', 'max:50'],
            'birth_year' => ['sometimes', 'nullable', 'integer', 'between:1900,' . date("Y")],
            'image' => ['sometimes', 'image', 'mimes:png,jpg,jpeg,bmp,svg', 'max:5000'],
            'gender' => ['required', 'in:M,F,O']
        ]);

        if ($request->has('first_name')) {
            $profile->first_name = $request->input('first_name');
        }

        if ($request->has('last_name')) {
            $profile->last_name = $request->input('last_name');
        }

        if ($request->has('birth_year')) {
            $profile->birth_year = $request->input('birth_year');
        }

        if ($request->has('image')) {

            if ( $profile->image != 'profiledefault.jpg') {
                $imagePathOld = public_path().'/uploads/'.$profile->image;
                unlink($imagePathOld);
            }
            $imageUploaded = $request->file('image');
            $imageName =  time() . $imageUploaded->getClientOriginalName();
            $imagePath = public_path('/uploads/');
            $imageUploaded->move($imagePath, $imageName);
            $profile->image = $imageName;
        }

        if ($request->has('gender')) {
            $profile->gender = $request->input('gender');
        }

        $profile->save();

        return view('profiles.show', [
            'profile' => $profile
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
