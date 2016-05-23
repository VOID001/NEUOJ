<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\User;

class AuthControllerTestSuite extends TestCase
{
    use DatabaseMigrations;

    private $adminObj;

    /**
     * @function testGETLoginAction
     * @input NULL
     *
     * @description checkLoginAction
     */
    public function testGETLoginAction()
    {
        $this->Seeder();

        /* Do basic visit on the login page */

        $this->visit('/auth/signin')
            ->see("Sign in to start your code");

        $this->visit('/auth/signin')
            ->click("I forget my password")
            ->seePageIs('/auth/request');

        $this->visit('/auth/signin')
            ->click("Register a new membership")
            ->seePageIs('/auth/signup');

        /* User cannot visit login when user already login */

        $authUser = $this->withSession(['username' => 'VOID001']);

        $authUser->visit('/auth/signin')
            ->seePageIs('/');

    }

    public function testPOSTLoginAction()
    {
        $this->Seeder();

        $this->assertTrue(true);
    }

    public function testGETregistAction()
    {
        $this->Seeder();

        /* Do basic visit on the register page */

        $this->visit('/auth/signup')
            ->seePageIs('/auth/signup');

        /* User cannot register when already login */

        $authUser = $this->withSession(['username' => 'VOID001']);

        $authUser->visit('/auth/signup')
            ->seePageIs('/');

    }

    public function testPOSTregistAction()
    {
        $this->Seeder();

        /* Make a correct POST first */

        $dataArr = [
            'username' => 'NEU_TEST',
            'pass' => 'NEU_TEST',
            'pass_confirmation' => 'NEU_TEST',
            'email' => 'example@mail.com'
        ];

        $success = $this->visit('/auth/signup')
            ->type('NEU_TEST', 'username')
            ->type('NEU_TEST', 'pass')
            ->type('NEU_TEST', 'pass_confirmation')
            ->type('example@mail.com', 'email')
            ->press('Sign up');

        $success->seePageIs('/dashboard/profile');
        $success->assertSessionHas('username', 'NEU_TEST');

        /* Provide a username already taken */

        $this->flushSession();
        $userNameErr = $this->visit('/auth/signup');
        foreach($dataArr as $key => $value)
        {
            $userNameErr->type($value, $key);
        }
        $userNameErr->type('NEU_TEST', 'username');
        $userNameErr = $userNameErr->press('Sign up');
        $userNameErr->see("The username has already been taken.");

        /* Do not provide username */

        $this->flushSession();
        $userNameErr = $this->visit('/auth/signup');
        foreach($dataArr as $key => $value)
        {
            $userNameErr->type($value, $key);
        }
        $userNameErr->type('', 'username');
        $userNameErr = $userNameErr->press('Sign up');
        $userNameErr->see("The username field is required");

        /* More testcase need to be created */

    }

    private function Seeder()
    {
        /* Create Admin Info */
        $this->adminObj = factory(App\User::class, 'admin')->create();
        factory(App\User::class, 10)->create();
    }
}
