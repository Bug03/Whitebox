<?php

namespace Tests\Unit\Services;

use App\Http\Services\CartService;
use App\Models\Cart;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class CartServiceTest extends TestCase
{
  use RefreshDatabase;

  protected $user;
  protected $cart;
  protected $product;

  public function setUp(): void
  {
    parent::setUp();

    // Tạo user test
    $this->user = User::factory()->create();

    // Tạo cart cho user
    $this->cart = Cart::factory()->create([
      'user_id' => $this->user->id,
      'cart_items' => json_encode([])
    ]);

    // Tạo sản phẩm test
    $this->product = Product::factory()->create();
  }

  /** @test */
  public function it_can_clear_cart()
  {
    // Login user
    Auth::login($this->user);

    // Thêm item vào cart
    $this->cart->saveCart($this->product->id, 2);

    // Gọi phương thức clear
    CartService::clear();

    // Kiểm tra cart đã trống
    $this->assertEmpty($this->cart->fresh()->getCartItems());
  }

  /** @test */
  public function it_can_get_list_cart()
  {
    // Login user
    Auth::login($this->user);

    // Thêm sản phẩm vào cart
    $this->cart->saveCart($this->product->id, 2);

    // Lấy danh sách cart
    $result = CartService::getListCart();

    // Kiểm tra kết quả
    $this->assertArrayHasKey('cartList', $result);
    $this->assertArrayHasKey('productList', $result);
    $this->assertArrayHasKey('weight', $result);
    $this->assertArrayHasKey('subtotal', $result);

    // Kiểm tra thông tin sản phẩm
    $this->assertEquals($this->product->id, $result['cartList'][$this->product->id]['id_product']);
    $this->assertEquals(2, $result['cartList'][$this->product->id]['quantity']);
  }

  /** @test */
  public function it_returns_empty_array_for_guest_user()
  {
    // Không login user
    $result = CartService::getListCart();

    // Kiểm tra kết quả rỗng
    $this->assertEmpty($result);
  }

  /** @test */
  public function it_can_show_cart_item()
  {
    // Login user
    Auth::login($this->user);

    // Thêm sản phẩm vào cart
    $this->cart->saveCart($this->product->id, 2);

    // Khởi tạo CartService
    $cartService = new CartService();

    // Lấy thông tin item
    $result = $cartService->show($this->product->id);

    // Kiểm tra kết quả
    $this->assertEquals($this->product->id, $result->id);
    $this->assertEquals(2, $result->quantity);
  }

  /** @test */
  public function it_can_destroy_cart_item()
  {
    // Login user
    Auth::login($this->user);

    // Thêm sản phẩm vào cart
    $this->cart->saveCart($this->product->id, 2);

    // Khởi tạo CartService
    $cartService = new CartService();

    // Xóa item
    $cartService->destroy($this->product->id);

    // Kiểm tra item đã bị xóa
    $this->assertArrayNotHasKey($this->product->id, $this->cart->fresh()->getCartItems());
  }

  /** @test */
  public function it_can_store_new_cart_item()
  {
    // Login user
    Auth::login($this->user);

    // Khởi tạo CartService
    $cartService = new CartService();

    // Thêm sản phẩm mới
    $cartService->store($this->product->id, 2);

    // Kiểm tra sản phẩm đã được thêm
    $cartItems = $this->cart->fresh()->getCartItems();
    $this->assertArrayHasKey($this->product->id, $cartItems);
    $this->assertEquals(2, $cartItems[$this->product->id]['quantity']);
  }

  /** @test */
  public function it_throws_exception_for_invalid_quantity()
  {
    // Login user
    Auth::login($this->user);

    // Khởi tạo CartService
    $cartService = new CartService();

    // Kiểm tra ném exception khi số lượng không hợp lệ
    $this->expectException(ValidationException::class);
    $cartService->store($this->product->id, 0);
  }

  /** @test */
  public function it_can_count_cart_items()
  {
    // Login user
    Auth::login($this->user);

    // Thêm sản phẩm vào cart
    $this->cart->saveCart($this->product->id, 2);

    // Đếm số lượng item
    $count = CartService::countCart();

    // Kiểm tra kết quả
    $this->assertEquals(1, $count);
  }
}