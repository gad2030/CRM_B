<?php

namespace Database\Factories;

use App\Models\Account;
use App\Models\Contact;
use App\Models\Opportunity;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Opportunity>
 */
class OpportunityFactory extends Factory
{
    protected $model = Opportunity::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(3),
            'amount' => fake()->randomFloat(2, 1000, 100000),
            'stage' => fake()->randomElement(['prospecting', 'qualification', 'proposal', 'negotiation', 'closed-won', 'closed-lost']),
            'close_date' => fake()->dateTimeBetween('now', '+1 year'),
            'probability' => fake()->numberBetween(0, 100),
            'account_id' => Account::factory(),
            'contact_id' => Contact::factory(),
            'owner_id' => User::factory(),
        ];
    }
}
