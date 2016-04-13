<?php

namespace HighIdeas\UsersOnline\Models;

use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Cache;

class UsersOnline extends User
{

    protected $table = "users";

    public function isOnline()
    {
         return Cache::has('user-is-online-' . $this->id);
    }
}
