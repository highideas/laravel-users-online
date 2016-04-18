<?php

use Illuminate\Support\Facades\Auth;
use HighIdeas\UsersOnline\Models\UsersOnline as Users;

class UsersOnlineTest extends TestCase
{
    public function test_dont_should_return_cache_because_wasnt_created()
    {
        $model = $this->makeUser();

        $this->assertFalse($model->isOnline());
    }

    public function test_should_return_the_user_cache_logged()
    {
        $model = $this->makeUser();

        Illuminate\Support\Facades\Cache::put('user-is-online-' . $model->id, true, 5);
        
        $this->assertTrue($model->isOnline());
    }

    public function test_should_return_all_users_cache_logged()
    {
        $user1 = $this->makeUser();

        Illuminate\Support\Facades\Cache::put('user-is-online-' . $user1->id, true, 5);

        /**
         * User Offline
         */
        $user2 = new \HighIdeas\UsersOnline\Models\UsersOnline;
        $user2->name = "Gabriel";
        $user2->email = "offline@teste.com";
        $user2->password = bcrypt("gabriel");
        $user2->save();


        $online = [];
        foreach (Users::all() as $user) { 
            if ($user->isOnline()) {
                $online[] = $user->id;
            }
        }
        
        $this->assertCount(1, $online);
        $this->assertEquals($online[0], $user1->id);
    }
}
