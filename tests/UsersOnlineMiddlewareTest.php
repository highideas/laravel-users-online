<?php

namespace HighIdeas\Tests;

use HighIdeas\UsersOnline\Middleware\UsersOnline;
use \Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class UsersOnlineMiddlewareTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->app->register(\HighIdeas\UsersOnline\Providers\UsersOnlineEventServiceProvider::class);

        \Route::middleware(
            \HighIdeas\UsersOnline\Middleware\UsersOnline::class
        )->any(
            '/_test/usersOnLine',
            function () { return 'OK';}
        );
    }

    public function test_user_should_return_is_online_when_logged_in()
    {
        $model = $this->makeUser();

        Auth::login($model);

        $response = $this->get('/_test/usersOnLine');

        $this->assertTrue($model->isOnline());
    }

    public function test_user_should_return_is_offline_when_not_logged_in()
    {
        $model = $this->makeUser();

        $response = $this->get('/_test/usersOnLine');

        $this->assertFalse($model->isOnline());
    }

    public function test_user_should_return_all_online()
    {
        $userOne   = $this->makeUser();

        $userTwo   = $this->makeUser();
        Auth::login($userTwo);
        $this->get('/_test/usersOnLine');

        $userThree = $this->makeUser();
        Auth::login($userThree);
        $this->get('/_test/usersOnLine');

        $userFour  = $this->makeUser();
        Auth::login($userFour);
        $this->get('/_test/usersOnLine');

        $this->get('/_test/usersOnLine');

        $user = $this->getUserModel();
        $this->assertEquals(3, $user->allOnline()->count());
    }

    public function test_user_should_list_all_logged_in_order_by_least_recent_online()
    {
        Carbon::setTestNow(Carbon::create('2017', 2, 22, 13, 45, 22));
        $userOne   = $this->makeUser();
        Auth::login($userOne);
        $this->get('/_test/usersOnLine');

        Carbon::setTestNow(Carbon::create('2017', 2, 22, 13, 40, 22));
        $userTwo   = $this->makeUser();
        Auth::login($userTwo);
        $this->get('/_test/usersOnLine');

        Carbon::setTestNow(Carbon::create('2017', 2, 22, 13, 50, 22));
        $userThree = $this->makeUser();
        Auth::login($userThree);
        $this->get('/_test/usersOnLine');

        Carbon::setTestNow();

        $user = $this->getUserModel();
        $expectedOrder = [
            $userTwo->id,
            $userOne->id,
            $userThree->id,
        ];
        $this->assertEquals($expectedOrder, $user->leastRecentOnline()->pluck('id')->all());
    }

    public function test_should_retunr_all_online_users_order_by_most_recent()
    {
        Carbon::setTestNow(Carbon::create('2017', 2, 22, 13, 45, 22));
        $userOne   = $this->makeUser();
        Auth::login($userOne);
        $this->get('/_test/usersOnLine');

        Carbon::setTestNow(Carbon::create('2017', 2, 22, 13, 40, 22));
        $userTwo   = $this->makeUser();
        Auth::login($userTwo);
        $this->get('/_test/usersOnLine');

        Carbon::setTestNow(Carbon::create('2017', 2, 22, 13, 50, 22));
        $userThree = $this->makeUser();
        Auth::login($userThree);
        $this->get('/_test/usersOnLine');

        Carbon::setTestNow();

        $user = $this->getUserModel();

        $expectedOrder = [
            $userThree->id,
            $userOne->id,
            $userTwo->id,
        ];
        $this->assertEquals($expectedOrder, $user->mostRecentOnline()->pluck('id')->all());
    }

    public function test_should_sacanear_gabriel()
    {
        $userOne   = $this->makeUser();
        Auth::login($userOne);
        $this->get('/_test/usersOnLine');

        $userTwo   = $this->makeUser();
        Auth::login($userTwo);
        $this->get('/_test/usersOnLine');

        $userThree = $this->makeUser();
        Auth::login($userThree);
        $this->get('/_test/usersOnLine');

        $userFour  = $this->makeUser();
        Auth::login($userFour);
        $this->get('/_test/usersOnLine');

        $this->get('/_test/usersOnLine');

        Auth::logout($userOne);
        $user = $this->getUserModel();
        $this->assertEquals(3, $user->allOnline()->count());
    }
}

