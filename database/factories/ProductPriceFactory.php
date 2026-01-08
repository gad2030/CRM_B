<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductPrice;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductPrice>
 */
class ProductPriceFactory extends Factory
{
    protected $model = ProductPrice::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startsAt = fake()->dateTimeBetween('-1 year', 'now');

        return [
            'product_id' => Product::factory(),
            'price' => fake()->randomFloat(2, 10, 10000),
            'starts_at' => $startsAt,
            'ends_at' => fake()->optional(0.3)->dateTimeBetween($startsAt, '+1 year'),
        ];
    }

    /**
     * Indicate that the price is currently active (no end date).
     */
    public function active(): static
    {
        return $this->state(fn(array $attributes) => [
            'starts_at' => now()->subDays(7),
            'ends_at' => null,
        ]);
    }

    /**
     * Indicate that the price is expired.
     */
    public function expired(): static
    {
        return $this->state(fn(array $attributes) => [
            'starts_at' => now()->subMonths(6),
            'ends_at' => now()->subMonths(1),
        ]);
    }

    /**
     * Indicate that the price will be active in the future.
     */
    public function upcoming(): static
    {
        return $this->state(fn(array $attributes) => [
            'starts_at' => now()->addDays(7),
            'ends_at' => null,
        ]);
    }
}
