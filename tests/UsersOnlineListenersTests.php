<?php

namespace HighIdeas\Tests;

use HighIdeas\UsersOnline\Middleware\UsersOnline;
use \HighIdeas\UsersOnline\Providers\UsersOnlineEventServiceProvider;
use \Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class UsersOnlineListenersTest extends TestCase
{
    public function setUp() : void
    {
        parent::setUp();
        $this->app->register(UsersOnlineEventServiceProvider::class);
    }

    public function test_user_should_return_is_online_when_logged_in()
    {
        $model = $this->makeUser();

        Auth::login($model);

        $this->assertTrue($model->isOnline());
    }

    public function test_user_should_return_is_offline_when_not_logged_in()
    {
        $model = $this->makeUser();

        $this->assertFalse($model->isOnline());
    }

    public function test_user_should_return_all_online()
    {
        $this->makeUser();

        $userTwo = $this->makeUser();
        Auth::login($userTwo);

        $userThree = $this->makeUser();
        Auth::login($userThree);

        $userFour = $this->makeUser();
        Auth::login($userFour);

        $user = $this->getUserModel();
        $this->assertEquals(3, $user->allOnline()->count());
    }

    public function test_user_should_list_all_logged_in_order_by_least_recent_online()
    {
        $userTwo   = $this->makeUser();
        Auth::login($userTwo);

        $userOne   = $this->makeUser();
        Auth::login($userOne);

        $userThree = $this->makeUser();
        Auth::login($userThree);

        Carbon::setTestNow();

        $user = $this->getUserModel();
        $expectedOrder = [
            $userTwo->id,
            $userOne->id,
            $userThree->id,
        ];
        $this->assertEquals($expectedOrder, $user->leastRecentOnline()->pluck('id')->all());
    }

    public function test_should_return_all_online_users_order_by_most_recent()
    {
        $userTwo   = $this->makeUser();
        Auth::login($userTwo);

        $userOne   = $this->makeUser();
        Auth::login($userOne);

        $userThree = $this->makeUser();
        Auth::login($userThree);

        Carbon::setTestNow();

        $user = $this->getUserModel();

        $expectedOrder = [
            $userThree->id,
            $userOne->id,
            $userTwo->id,
        ];
        $this->assertEquals($expectedOrder, $user->mostRecentOnline()->pluck('id')->all());
    }

    public function test_should_remove_offline_users()
    {
        $userOne   = $this->makeUser();
        Auth::login($userOne);

        $userTwo   = $this->makeUser();
        Auth::login($userTwo);

        $userThree = $this->makeUser();
        Auth::login($userThree);

        $userFour  = $this->makeUser();
        Auth::login($userFour);

        Auth::logout($userOne);
        $user = $this->getUserModel();
        $this->assertEquals(3, $user->allOnline()->count());
    }
}

