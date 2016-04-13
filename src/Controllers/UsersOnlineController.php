<?php

namespace HighIdeas\UsersOnline\Controllers;

use App\Http\Controllers\Controller;
use HighIdeas\UsersOnline\Models\UsersOnline;

class UsersOnlineController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show all users online.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = UsersOnline::all();
        return view('usersonline::index', ['users' => $users]);
    }
}

