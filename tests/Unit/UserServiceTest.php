<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Tests\TestCase;
use Illuminate\Support\Facades\Event;
use Illuminate\Http\Request;
use Mockery;
use Illuminate\Support\Facades\Hash;

class UserServiceTest extends TestCase
{

    use RefreshDatabase;


    public function test_get_all_user_list_except_authenticate_user(): void
    {
        User::factory()->create()->count(2);
        $authenticatedUser = User::factory()->create();
        $this->actingAs($authenticatedUser);

        $users = $this->userService->list();

        $this->assertCount(2, $users);
        $this->assertNotContains($authenticatedUser, $users);
    }



    public function test_get_user_by_id()
    {
        $user = User::factory()->create();

        $retrievedUser = $this->userService->getById($user->id);

        $this->assertInstanceOf(User::class, $retrievedUser);
        $this->assertEquals($user->id, $retrievedUser->id);
    }



    public function test_throws_exception_if_user_not_found()
    {
        $this->expectException(ModelNotFoundException::class);
        $this->userService->getById(9999);
    }



    public function test_user_store()
    {
        $fileHelperMock = Mockery::mock('overload:App\Helpers\FileHelper');
        $fileHelperMock->shouldReceive('uploadImage')->andReturn('fake_image_path');

        Event::fake();

        $requestData = [
            'name' => 'Test name',
            'email' => 'test@gmail.com',
            'password' => '12345678'

        ];

        $createdUser = $this->userService->store(new Request($requestData));

        $this->assertNotNull($createdUser);
        $this->assertInstanceOf(User::class, $createdUser);
        $this->assertEquals('test@gmail.com', $createdUser->email);

        Event::assertDispatched(\App\Events\AddressEvent::class, function ($event) use ($createdUser) {
            return $event->user->id === $createdUser->id;
        });
    }



    public function test_user_info_update()
    {
        $fileHelperMock = Mockery::mock('overload:App\Helpers\FileHelper');
        $fileHelperMock->shouldReceive('uploadImage')->andReturn('fake_image_path');

        Event::fake();

        $user = User::factory()->create();

        $requestData = [
            'name' => 'Updated Name',
            'email' => 'updated_email@example.com',
            'password' => 'new_password',
        ];

        $updatedUser = $this->userService->update(new Request($requestData), $user->id);

        $user = User::find($user->id);

        $this->assertInstanceOf(User::class, $updatedUser);
        $this->assertEquals($requestData['name'], $updatedUser->name);
        $this->assertEquals($requestData['email'], $updatedUser->email);
        $this->assertTrue(Hash::check($requestData['password'], $updatedUser->password));

        Event::assertDispatched(\App\Events\AddressEvent::class, function ($event) use ($updatedUser) {
            return $event->user->id === $updatedUser->id;
        });
    }



    public function test_soft_delete_user_by_id()
    {
        $user = User::factory()->create();

        $this->userService->delete($user->id);

        $this->assertSoftDeleted($user);
    }



    public function test_get_trashed_user_list()
    {
        $trashedUser = User::factory()->create(['deleted_at' => now()]);

        $nonTrashedUser = User::factory()->create();

        $trashedUsers = $this->userService->trashedUserList();

        $this->assertCount(1, $trashedUsers);
        $this->assertTrue($trashedUsers->contains($trashedUser));
        $this->assertFalse($trashedUsers->contains($nonTrashedUser));

    }



    public function test_trashed_user_restore()
    {
        $trashedUser = User::factory()->create(['deleted_at' => now()]);

        $this->userService->trashedUserRestore($trashedUser->id);

        $restoredUser = User::withTrashed()->find($trashedUser->id);

        $this->assertNotNull($restoredUser);
        $this->assertFalse($restoredUser->trashed());

    }



    public function test_trashed_user_permanent_delete()
    {
        $trashedUser = User::factory()->create(['deleted_at' => now()]);

        $this->userService->trashedUserPermanentDelete($trashedUser->id);

        $this->assertDatabaseMissing('users', ['id' => $trashedUser->id]);

    }
}
