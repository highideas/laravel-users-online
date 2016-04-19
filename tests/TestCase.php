<?php

use Illuminate\Database\Capsule\Manager as DB;


abstract class TestCase extends Orchestra\Testbench\TestCase
{

    public function setUp()
    {
        parent::setUp();
        $this->setUpDatabase();
        $this->migrateTables();

    }

    protected function setUpDatabase()
    {
        $database = new DB;
        $database->addConnection(['driver' => 'sqlite', 'database' => ':memory:']);
        $database->bootEloquent();
        $database->setAsGlobal();
    }

    protected function migrateTables()
    {
        DB::schema()->create('users', function($table){
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
    } 

    public function makeUser()
    {
        $user = new User;
        $user->name = "Gabriel";
        $user->email = "teste@teste.com";
        $user->password = bcrypt("gabriel");
        $user->save();

        return $user;
    }
}

class User extends Illuminate\Foundation\Auth\User
{
    use \HighIdeas\UsersOnline\Traits\UsersOnlineTrait;
}
