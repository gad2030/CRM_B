<?php

namespace Database\Factories;

use App\Models\Account;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Account>
 */
class AccountFactory extends Factory
{
    protected $model = Account::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->company(),
            'industry' => fake()->randomElement(['Technology', 'Healthcare', 'Finance', 'Retail', 'Manufacturing']),
            'website' => fake()->url(),
            'phone' => fake()->phoneNumber(),
            'address' => fake()->address(),
            'billing_address' => fake()->address(),
            'owner_id' => User::factory(),
        ];
    }
}
