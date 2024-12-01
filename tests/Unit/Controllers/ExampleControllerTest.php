<?php

namespace Tests\Unit\Controller;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExampleControllerTest extends TestCase
{
    use RefreshDatabase; // Sử dụng nếu cần reset database sau mỗi test

    public function setUp(): void
    {
        parent::setUp();
        // Khởi tạo các giá trị cần thiết trước mỗi test
    }

    public function test_example_function()
    {
        // Arrange - Chuẩn bị dữ liệu
        $expectedResult = 'expected value';

        // Act - Thực thi hành động cần test
        $result = $this->get('/api/example');

        // Assert - Kiểm tra kết quả
        $result->assertStatus(200);
        $this->assertEquals($expectedResult, $result->json('data'));
    }
}
