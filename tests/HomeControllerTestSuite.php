<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class HomeControllerTestSuite extends TestCase
{
    use DatabaseMigrations;

    public function testShowHome()
    {
        $authUser = $this->withSession(['username' => 'admin']);

        $authUser->visit('/')
            ->see('admin');

        $this->flushSession();
        $guestUser = $this;

        $guestUser->visit('/')
            ->see('Sign in');
    }
}
