<?php

use App\Models\Discount;
use App\Models\CartItem;
use App\Models\Product;
use App\Services\DiscountService;
use Illuminate\Database\Eloquent\Collection;

beforeEach(function () {
    $product = Product::factory()->create([
        'price' => 100,
    ]);
    $this->discount = Discount::factory()->create([
        'product_id'           => $product->id,
        'minimum_order_amount' => 50,
        'discount_percent'     => 5,
    ]);
    $this->cartItem = CartItem::factory()->create([
        'product_id' => $product->id,
    ]);
});

it('calculates product discount correctly', function () {
    $cartItems = new Collection([$this->cartItem]);
    $totalAmount = 100.00;

    $this->discount->type = Discount::TYPE_PRODUCT_DISCOUNT;

    $discountService = new DiscountService();
    $result = $discountService->calculateTotalAmountWithDiscounts(
        $cartItems,
        $totalAmount,
        $this->discount->name,
    );

    expect($result)->toBe(95.00);
});

it('calculates total discount correctly', function () {
    $cartItems = new Collection([$this->cartItem]);
    $totalAmount = 100.00;

    $this->discount->type = Discount::TYPE_TOTAL_DISCOUNT;

    $discountService = new DiscountService();
    $result = $discountService->calculateTotalAmountWithDiscounts(
        $cartItems,
        $totalAmount,
        $this->discount->name,
    );

    expect($result)->toBe(95.00);
});

it('calculates combined discount correctly', function () {
    $cartItems = new Collection([$this->cartItem]);
    $totalAmount = 100.00;

    $this->discount->type = Discount::TYPE_COMBINED_DISCOUNT;

    $discountService = new DiscountService();
    $result = $discountService->calculateTotalAmountWithDiscounts(
        $cartItems,
        $totalAmount,
        $this->discount->name,
    );

    expect($result)->toBe(95.00);
});
