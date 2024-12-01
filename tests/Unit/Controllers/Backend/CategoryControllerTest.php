<?php

namespace Tests\Unit\Controllers\Backend;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CategoryControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Disable middleware for testing
        $this->withoutMiddleware();

        // Create admin user
        $this->admin = User::factory()->create([
            'email' => 'admin@gmail.com',
            'password' => Hash::make('123'),
            ## Add role_id to the factory
            'role_id' => 1,
        ]);
    }

    public function test_index_displays_categories()
    {
        $this->actingAs($this->admin);

        $response = $this->get(route('admin.category.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.category.index');
    }

    public function test_create_displays_create_view()
    {
        $this->actingAs($this->admin);

        $response = $this->get(route('admin.category.create'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.category.create');
    }

    public function test_edit_displays_edit_view()
    {
        $this->actingAs($this->admin);

        $category = Category::factory()->create();

        $response = $this->get(route('admin.category.edit', $category->id));

        $response->assertStatus(200);
        $response->assertViewIs('admin.category.edit');
        $response->assertViewHas('category', $category);
    }
}
