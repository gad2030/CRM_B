<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $price = fake()->randomFloat(2, 10, 10000);
        $costPrice = fake()->randomFloat(2, 5, $price * 0.7);

        return [
            'name' => fake()->words(3, true),
            'sku' => fake()->optional()->unique()->bothify('SKU-####-????'),
            'description' => fake()->optional()->paragraph(),
            'price' => $price,
            'cost_price' => $costPrice,
            'category_id' => fake()->optional()->randomElement([null, Category::factory()]),
            'owner_id' => User::factory(),
            'is_active' => fake()->boolean(80), // 80% chance of being active
        ];
    }

    /**
     * Indicate that the product is active.
     */
    public function active(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_active' => true,
        ]);
    }

    /**
     * Indicate that the product is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_active' => false,
        ]);
    }
}
