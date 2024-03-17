<?php

namespace Tests\Unit;

use App\Http\Requests\UserCreateRequest;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Tests\TestCase;
use Illuminate\Support\Facades\Event;
use Illuminate\Http\Request;
use Mockery;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserServiceTest extends TestCase
{
    use RefreshDatabase;


    public function test_get_all_user_list_except_authenticate_user(): void
    {
        User::factory()->create()->count(2);
        $authenticatedUser = User::factory()->create();
        $this->actingAs($authenticatedUser);

        $users = (new UserService())->list();

        $this->assertCount(2, $users);
        $this->assertNotContains($authenticatedUser, $users);
    }


    public function test_get_user_by_id()
    {
        $user = User::factory()->create();

        $retrievedUser = (new UserService())->getById($user->id);

        $this->assertInstanceOf(User::class, $retrievedUser);
        $this->assertEquals($user->id, $retrievedUser->id);
    }


    public function test_throws_exception_if_user_not_found()
    {
        // Invoke the method being tested
        $userService = new UserService();

        // Assertions
        $this->expectException(ModelNotFoundException::class); // Assert that a ModelNotFoundException is thrown
        $userService->getById(9999); // Try to restore a trashed user that doesn't exist
    }


    public function test_user_store()
    {
        // Mock FileHelper
        $fileHelperMock = Mockery::mock('overload:App\Helpers\FileHelper');
        $fileHelperMock->shouldReceive('uploadImage')->andReturn('fake_image_path');

        // Mock AddressEvent
        Event::fake();

        // Create a request with validated data
        $requestData = [
            'name' => 'Test name',
            'email' => 'test@gmail.com',
            'password' => '12345678'
            // Add other validated data here
        ];

        // Invoke the method being tested
        $userService = new UserService();
        $createdUser = $userService->store(new Request($requestData));

        // Assertions
        $this->assertNotNull($createdUser); // Assert that a user is created
        $this->assertInstanceOf(User::class, $createdUser); // Assert that the created user is an instance of User
        $this->assertEquals('test@gmail.com', $createdUser->email); // Assert that the user's name matches the provided data
        // Add more assertions if needed

        // Assert that the AddressEvent event is triggered
        Event::assertDispatched(\App\Events\AddressEvent::class, function ($event) use ($createdUser) {
            return $event->user->id === $createdUser->id;
        });
    }



    public function test_user_info_update()
    {
        // Mock FileHelper
        $fileHelperMock = Mockery::mock('overload:App\Helpers\FileHelper');
        $fileHelperMock->shouldReceive('uploadImage')->andReturn('fake_image_path');

        // Mock AddressEvent
        Event::fake();

        // Create a user
        $user = User::factory()->create();

        // Create request data for update
        $requestData = [
            'name' => 'Updated Name',
            'email' => 'updated_email@example.com',
            'password' => 'new_password',
        ];

        // Invoke the method being tested
        $userService = new UserService();
        $updatedUser = $userService->update(new Request($requestData), $user->id);

        // Retrieve the updated user from the database
        $user = User::find($user->id);

        // Assertions
        $this->assertInstanceOf(User::class, $updatedUser); // Assert that an updated user is returned
        $this->assertEquals($requestData['name'], $updatedUser->name); // Assert that the user's name is updated
        $this->assertEquals($requestData['email'], $updatedUser->email); // Assert that the user's email is updated
        $this->assertTrue(Hash::check($requestData['password'], $updatedUser->password)); // Assert that the user's password is updated and hashed correctly

        // Assert that the AddressEvent event is triggered
        Event::assertDispatched(\App\Events\AddressEvent::class, function ($event) use ($updatedUser) {
            return $event->user->id === $updatedUser->id;
        });
    }


    
    public function test_soft_delete_user_by_id()
    {
        $user = User::factory()->create();

        $userService = new UserService();
        $userService->delete($user->id);

        $this->assertSoftDeleted($user);
    }



    public function test_get_trashed_user_list()
    {
        // Create a trashed user
        $trashedUser = User::factory()->create(['deleted_at' => now()]);

        // Create a non-trashed user
        $nonTrashedUser = User::factory()->create();

        // Invoke the method being tested
        $userService = new UserService();
        $trashedUsers = $userService->trashedUserList();

        // Assertions
        $this->assertCount(1, $trashedUsers); // Assert that the correct number of trashed users is returned
        $this->assertTrue($trashedUsers->contains($trashedUser)); // Assert that the trashed user is in the list
        $this->assertFalse($trashedUsers->contains($nonTrashedUser)); // Assert that the non-trashed user is not in the list

    }




    public function test_trashed_user_restore()
    {
        // Create a trashed user
        $trashedUser = User::factory()->create(['deleted_at' => now()]);

        // Invoke the method being tested
        $userService = new UserService();
        $userService->trashedUserRestore($trashedUser->id);

        // Retrieve the user from the database after restoration
        $restoredUser = User::withTrashed()->find($trashedUser->id);

        // Assertions
        $this->assertNotNull($restoredUser); // Assert that the user has been restored
        $this->assertFalse($restoredUser->trashed()); // Assert that the user is no longer trashed

    }



    public function test_trashed_user_permanent_delete()
    {
        // Create a trashed user
        $trashedUser = User::factory()->create(['deleted_at' => now()]);

        // Invoke the method being tested
        $userService = new UserService();
        $userService->trashedUserPermanentDelete($trashedUser->id);

        // Assertions
        $this->assertDatabaseMissing('users', ['id' => $trashedUser->id]); // Assert that the user is permanently deleted from the database

    }
}
