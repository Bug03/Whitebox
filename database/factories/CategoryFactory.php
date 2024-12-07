<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CategoryFactory extends Factory
{
  protected $model = Category::class;

  public function definition()
  {
    $name = fake()->word();
    return [
      'name' => $name,
      'icon' => fake()->imageUrl(),
      'status' => $this->faker->numberBetween(0, 1), // Status có thể là 0 hoặc 1,
      'slug' => Str::slug($name),
    ];
  }
}