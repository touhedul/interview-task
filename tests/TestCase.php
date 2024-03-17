<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    //

    public User $user;

    public function setUp(): void
    {
        parent::setUp();
        // Artisan::call('db:seed');
        $this->user = $this->createUser();
    }

    public function createUser()
    {
        $user = User::factory()->create();
        return $user;
    }
}
