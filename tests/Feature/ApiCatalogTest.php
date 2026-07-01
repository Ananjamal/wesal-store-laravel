<?php

namespace Tests\Feature;

use App\Enums\ProductStatus;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiCatalogTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_products_returns_paginated_published_products(): void
    {
        // Arrange: one published and one draft product
        $category = Category::factory()->create(['is_active' => true]);
        Product::factory()->create(['status' => ProductStatus::Published, 'category_id' => $category->id]);
        Product::factory()->create(['status' => ProductStatus::Draft, 'category_id' => $category->id]);

        // Act
        $response = $this->getJson('/api/catalog/products');

        // Assert
        $response->assertOk()
            ->assertJsonStructure([
                'success',
                'data'  => [['id', 'name', 'slug', 'price', 'status']],
                'meta'  => ['next_cursor', 'per_page'],
            ])
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('success', true);
    }

    public function test_get_products_filters_by_category(): void
    {
        $catA = Category::factory()->create(['is_active' => true]);
        $catB = Category::factory()->create(['is_active' => true]);

        Product::factory()->create(['status' => ProductStatus::Published, 'category_id' => $catA->id]);
        Product::factory()->create(['status' => ProductStatus::Published, 'category_id' => $catB->id]);

        $response = $this->getJson("/api/catalog/products?category_id={$catA->id}");

        $response->assertOk()
            ->assertJsonCount(1, 'data');
    }

    public function test_get_single_product_returns_detail(): void
    {
        $category = Category::factory()->create(['is_active' => true]);
        $product  = Product::factory()->create(['status' => ProductStatus::Published, 'category_id' => $category->id]);

        $response = $this->getJson("/api/catalog/products/{$product->id}");

        $response->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.id', $product->id);
    }
}
