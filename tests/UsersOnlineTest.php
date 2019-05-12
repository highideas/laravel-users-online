<?php

namespace HighIdeas\Tests;

use Carbon\Carbon;

class UsersOnlineTest extends TestCase
{
    public function test_dont_should_return_cache_because_wasnt_created()
    {
        $model = $this->makeUser();

        $this->assertFalse($model->isOnline());
    }

    public function test_should_return_chache_key()
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
        $model->setCache();

        $this->assertTrue($model->isOnline());
    }

    public function test_should_logout_user_by_time()
    {
        $model = $this->makeUser();
        $model->setCache(300);

        Carbon::setTestNow(Carbon::now()->addMinutes(10));
        $this->assertFalse($model->isOnline());
    }

    public function test_shoud_clear_cache_when_user_do_logout()
    {
        $model = $this->makeUser();
        $model->setCache();

        $model->pullCache();

        $this->assertFalse($model->isOnline());

    }

    public function test_should_return_all_users_online()
    {
        $user1 = $this->makeUser();
        $user1->setCache();

        $user2 = $this->makeUser();
        $user2->setCache();

        $user3 = $this->makeUser();
        $user3->setCache();
        $user3->pullCache();

        $user = $this->getUserModel();

        $this->assertEquals(2, $user->allOnline()->count());
    }

    public function test_should_retunr_all_online_users_order_by_most_recent()
    {

        $user1 = $this->makeUser();
        $user1->setCache();

        $user2 = $this->makeUser();
        $user2->setCache();

        $user3 = $this->makeUser();
        $user3->setCache();

        $user = $this->getUserModel();

        $expectedOrder = [
            $user3->id,
            $user2->id,
            $user1->id,
        ];
        $this->assertEquals($expectedOrder, $user->mostRecentOnline()->pluck('id')->all());
    }

    public function test_should_retunr_all_online_users_order_by_least_recent()
    {
        $user1 = $this->makeUser();
        $user1->setCache();

        $user2 = $this->makeUser();
        $user2->setCache();

        $user3 = $this->makeUser();
        $user3->setCache();

        Carbon::setTestNow();

        $user = $this->getUserModel();

        $expectedOrder = [
            $user1->id,
            $user2->id,
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
        $user->setCache();

        $this->assertEquals(
            [
                'cachedAt' => Carbon::now(),
                'user' => $user,
            ],
            $user->getCacheContent()
        );
    }
}

