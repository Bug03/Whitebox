<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
  protected $model = Product::class;

  public function definition()
  {
    $name = fake()->word();
    return [
      'name' => $name,
      'slug' => Str::slug($name),
      'thumb_image' => fake()->imageUrl(),
      'category_id' => Category::factory(),
      'description' => fake()->sentence(),
      'content' => fake()->paragraph(),
      'price' => fake()->numberBetween(100, 10000),
      'status' => fake()->numberBetween(0, 1),
      'weight' => fake()->numberBetween(100, 1000),
    ];
  }
}