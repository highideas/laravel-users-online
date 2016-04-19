<?php

namespace HighIdeas\UsersOnline\Controllers;

use App\Http\Controllers\Controller;
use App\User;

class UsersOnlineController extends Controller
{


    /**
     * Show all users online.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();
        return view('usersonline::index', ['users' => $users]);
    }
}

