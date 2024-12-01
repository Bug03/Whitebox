<?php

namespace Tests\Feature\Category;

use App\Models\Category;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\IntegrationTestCase;
use Tests\TestCase;
use Tests\AdminIntegrationTestCase;

class CategoryControllerTest extends AdminIntegrationTestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

    }

    public function test_index_displays_categories()
    {

        $response = $this->actingAsAdmin()->get(route('admin.category.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.category.index');
    }

    public function test_create_displays_create_view()
    {

        $response = $this->actingAsAdmin()->get(route('admin.category.create'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.category.create');
    }

    public function test_edit_displays_edit_view()
    {

        $category = Category::factory()->create();

        $response = $this->actingAsAdmin()->get(route('admin.category.edit', $category->id));

        $response->assertStatus(200);
        $response->assertViewIs('admin.category.edit');
        $response->assertViewHas('category', $category);
    }
}
