<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\User;
use Carbon\Carbon;

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

    public function test_should_return_all_users_online()
    {
        $user1 = $this->makeUser();
        Auth::login($user1);
        Auth::user()->setCache();

        $user2 = $this->makeUser();
        Auth::login($user2);
        Auth::user()->setCache();

        $user3 = $this->makeUser();
        Auth::login($user3);

        $user = $this->getUserModel();

        $this->assertEquals(2, $user->allOnline()->count());
    }

    public function test_should_retunr_all_online_users_order_by_most_recent()
    {
        Carbon::setTestNow(Carbon::create('2017', 2, 22, 13, 45, 22));
        $user1 = $this->makeUser();
        Auth::login($user1);
        Auth::user()->setCache(10);

        Carbon::setTestNow(Carbon::create('2017', 2, 22, 13, 40, 22));
        $user2 = $this->makeUser();
        Auth::login($user2);
        Auth::user()->setCache(5);

        Carbon::setTestNow(Carbon::create('2017', 2, 22, 13, 50, 22));
        $user3 = $this->makeUser();
        Auth::login($user3);
        Auth::user()->setCache(15);

        Carbon::setTestNow();

        $user = $this->getUserModel();

        $expectedOrder = [
            $user3->id,
            $user1->id,
            $user2->id,
        ];
        $this->assertEquals($expectedOrder, $user->mostRecentOnline()->pluck('id')->all());
    }

    public function test_should_retunr_all_online_users_order_by_least_recent()
    {
        Carbon::setTestNow(Carbon::create('2017', 2, 22, 13, 45, 22));
        $user1 = $this->makeUser();
        Auth::login($user1);
        Auth::user()->setCache(10);

        Carbon::setTestNow(Carbon::create('2017', 2, 22, 13, 40, 22));
        $user2 = $this->makeUser();
        Auth::login($user2);
        Auth::user()->setCache(5);

        Carbon::setTestNow(Carbon::create('2017', 2, 22, 13, 50, 22));
        $user3 = $this->makeUser();
        Auth::login($user3);
        Auth::user()->setCache(15);

        Carbon::setTestNow();

        $user = $this->getUserModel();

        $expectedOrder = [
            $user2->id,
            $user1->id,
            $user3->id,
        ];
        $this->assertEquals($expectedOrder, $user->leastRecentOnline()->pluck('id')->all());
    }

    public function test_get_cached_at_should_return_zero_when_cache_not_found()
    {
        $user = $this->makeUser();

        $this->assertEquals(0, $user->getCachedAt());
    }

    public function test_get_cache_content_should_return_cached_info_if_available()
    {
        Carbon::setTestNow(Carbon::create('2017', 2, 22, 13, 50, 22));
        $user = $this->makeUser();
        Auth::login($user);
        Auth::user()->setCache();

        $this->assertEquals(
            [
                'cachedAt' => Carbon::now(),
                'user' => $user,
            ],
            $user->getCacheContent()
        );
    }
}

