<?php

namespace Chatty\Http\Controllers\Timeline;

use Chatty\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Chatty\Status;

class TimelineController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    { }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        if (Auth::check()) {
            $statuses = Status::notReply()->where(function ($query) {
                $profile = Auth::user()->profile;
                return $query->where('profile_id', $profile->id)
                    ->orWhereIn(
                        'profile_id',
                        $profile->friends()->pluck('id')
                    );
            })->orderBy('created_at', 'desc')
                ->paginate(5);

            return view('timeline.index', [
                'statuses' => $statuses
            ]);
        }

        return view('home');
    }
}
