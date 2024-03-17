<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserAddress>
 */
class UserAddressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $userIds = User::pluck('id')->toArray();
        $locations = ['Dhaka','Khulna','Barishal','Chittagram','Rajsahi','Rongpur','Cumilla'];
        
        return [
            'user_id' => fake()->randomElement($userIds),
            'location' => fake()->randomElement($locations),
        ];
    }
}
