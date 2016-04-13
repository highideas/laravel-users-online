<?php

namespace HighIdeas\UsersOnline\Controllers;

use App\Http\Controllers\Controller;
use HighIdeas\UsersOnline\Models\UsersOnline;

class UsersOnlineController extends Controller
{
    public function index()
    {
        $users = UsersOnline::all();
        return view('usersonline::index', ['users' => $users]);
    }
}

