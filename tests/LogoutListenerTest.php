<?php

namespace HighIdeas\Tests;

use \Illuminate\Support\Facades\Auth;
use \Illuminate\Auth\Events\Logout;
use Carbon\Carbon;

use \HighIdeas\UsersOnline\Listeners\LogoutListener;

class LogoutListenerTest extends TestCase
{

    public function test_listener_should_receive_a_logout_event_and_remove_user_of_online_list()
    {
        $userOne = $this->makeUser();
        Auth::login($userOne);
        Auth::user()->setCache();

        $this->assertTrue($userOne->isOnline());

        $listener = new LogoutListener();
        $listener->handle(new Logout("session", $userOne));

        $user = $this->getUserModel();

        $this->assertFalse($user->isOnline());
    }

    public function test_should_return_all_users_online()
    {
        $userOne = $this->makeUser();
        Auth::login($userOne);
        Auth::user()->setCache();

        $userTwo = $this->makeUser();
        Auth::login($userTwo);
        Auth::user()->setCache();

        $userThree = $this->makeUser();
        Auth::login($userThree);
        Auth::user()->setCache();

        $user = $this->getUserModel();

        $this->assertEquals(3, $user->allOnline()->count());

        $listener = new LogoutListener();
        $listener->handle(new Logout("session", $userThree));

        $this->assertEquals(2, $user->allOnline()->count());
    }

    public function test_should_list_all_logged_users_ordered_by_least_recent_online()
    {
        $userOne   = $this->makeUser();
        Auth::login($userOne);
        Auth::user()->setCache();

        $userTwo   = $this->makeUser();
        Auth::login($userTwo);
        Auth::user()->setCache();

        $userThree = $this->makeUser();
        Auth::login($userThree);
        Auth::user()->setCache();

        Carbon::setTestNow();

        $listener = new LogoutListener();
        $listener->handle(new Logout("session", $userTwo));

        $user = $this->getUserModel();
        $expectedOrder = [
            $userOne->id,
            $userThree->id,
        ];
        $this->assertEquals($expectedOrder, $user->leastRecentOnline()->pluck('id')->all());
    }

    public function test_should_return_all_logged_users_ordered_by_most_recent_without_user_that_got_out()
    {
        $userOne   = $this->makeUser();
        Auth::login($userOne);
        Auth::user()->setCache();

        $userTwo   = $this->makeUser();
        Auth::login($userTwo);
        Auth::user()->setCache();

        $userThree = $this->makeUser();
        Auth::login($userThree);
        Auth::user()->setCache();

        $listener = new LogoutListener();
        $listener->handle(new Logout("session", $userTwo));

        $user = $this->getUserModel();

        $expectedOrder = [
            $userThree->id,
            $userOne->id,
        ];
        $this->assertEquals($expectedOrder, $user->mostRecentOnline()->pluck('id')->all());
    }
}
