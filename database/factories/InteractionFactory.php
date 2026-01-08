<?php

namespace Database\Factories;

use App\Models\Account;
use App\Models\Contact;
use App\Models\Interaction;
use App\Models\Lead;
use App\Models\Opportunity;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Interaction>
 */
class InteractionFactory extends Factory
{
    protected $model = Interaction::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'type' => fake()->randomElement(['call', 'email', 'meeting', 'note']),
            'subject' => fake()->sentence(),
            'description' => fake()->paragraph(),
            'date' => fake()->dateTimeBetween('-1 month', 'now'),
            'user_id' => User::factory(),
            'account_id' => Account::factory(),
            'contact_id' => null,
            'lead_id' => null,
            'opportunity_id' => null,
        ];
    }
}
