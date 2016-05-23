<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Http\Controllers\RoleController;

class RoleAndAbleTestSuite extends TestCase
{

    private $adminObj, $teacherObj, $stuObj;

    use DatabaseMigrations;

    /**
     * First test for teacher ability
     * teacher now can only view code and share
     * the same ability of student
     *
     * @return void
     */
    public function testTeacherAbility()
    {
        $this->Seeder();
        $this->withSession(['uid' => $this->teacherObj->uid]);

        $roleCheck = new RoleController;
        $this->assertTrue($roleCheck->is('teacher'));
        $this->assertTrue($roleCheck->is('able-view-code'));
        $this->assertFalse($roleCheck->is('admin'));

    }

    public function Seeder()
    {
        $this->adminObj = factory(App\User::class, 'admin')->create();
        factory(App\User::class, 'admin')->create();
        $this->stuObj = factory(App\User::class, 10)->create();
        $this->teacherObj = factory(App\User::class, 'teacher')->create();
    }

}
