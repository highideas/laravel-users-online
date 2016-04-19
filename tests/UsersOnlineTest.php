<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\User;

class UsersOnlineTest extends TestCase
{
    public function test_dont_should_return_cache_because_wasnt_created()
    {
        $model = $this->makeUser();

        $this->assertFalse($model->isOnline());
    }

    public function testReturnChachekey()
    {
        $model = $this->makeUser();
        $key = $model->getCacheKey();

        $this->assertEquals(
            'UserOnline-1',
            $key
        );
    }

    public function test_should_return_the_user_cache_logged()
    {
        $model = $this->makeUser();
        Auth::login($model);
        Auth::user()->setCache();
        
        $this->assertTrue($model->isOnline());
    }
    
    public function test_shoud_clear_cache_when_user_do_logout()
    {
        $model = $this->makeUser();
        Auth::login($model);
        Auth::user()->setCache();

        $model->pullCache();
        Auth::logout();

        $this->assertFalse($model->isOnline());
        
    }
}
