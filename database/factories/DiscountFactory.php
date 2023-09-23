<?php
declare(strict_types=1);

namespace Database\Factories;

use App\Models\Discount;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Discount>
 */
class DiscountFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'                 => fake()->word,
            'type'                 => fake()->randomElement(Discount::getDiscountTypes()),
            'product_id'           => Product::factory(),
            'minimum_order_amount' => $this->faker->randomFloat(1, 10, 100),
            'discount_percent'     => $this->faker->randomFloat(1, 5, 50),
        ];
    }
}
