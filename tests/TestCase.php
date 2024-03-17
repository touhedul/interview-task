<?php

namespace Tests;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    //

    public User $user;
    public UserService $userService;

    public function setUp(): void
    {
        parent::setUp();
        // Artisan::call('db:seed');
        $this->user = $this->createUser();
        $this->userService = new UserService();
    }

    public function createUser()
    {
        $user = User::factory()->create();
        return $user;
    }



}
