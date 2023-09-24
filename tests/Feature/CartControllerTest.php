<?php

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Contracts\View\View;
use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\put;
use function Pest\Laravel\withCookie;

it('can show cart (not authorized)', function () {
    $cart = Cart::factory()->create();

    $cartItems = CartItem::factory(3)->create(['cart_id' => $cart->id]);

    $response = withCookie('session_id', $cart->session_id)
        ->get(route('cart.show', ['discount' => 'qwe']))
        ->assertOk();

    expect($response->original)->toBeInstanceOf(View::class);

    foreach ($cartItems as $item) {
        expect($response->content())->toContain($item->product->name);
    }
});


it('can show cart (authorized)', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $cart = Cart::factory()->create(['user_id' => $user->id]);

    $cartItems = CartItem::factory(3)->create(['cart_id' => $cart->id]);

    $response = get(route('cart.show'))
        ->assertOk();

    expect($response->original)->toBeInstanceOf(View::class);

    foreach ($cartItems as $item) {
        expect($response->content())->toContain($item->product->name);
    }
});

it('can add item to cart', function () {
    $product = Product::factory()->create();

    post(route('cart.add'), ['product_id' => $product->id])
        ->assertRedirect(route('cart.show'));

    expect(CartItem::where('product_id', $product->id)->exists())->toBeTrue();
});

it('can update quantity of cart item', function () {
    $cart = Cart::factory()->create();

    $cartItem = CartItem::factory()->create(['cart_id' => $cart->id]);

    $newQuantity = 5;
    put(route('cart.update', $cartItem), ['quantity' => $newQuantity])
        ->assertRedirect(route('products.index'));

    expect($cartItem->fresh()->quantity)->toBe($newQuantity);
});

it('can destroy cart item', function () {
    $cart = Cart::factory()->create();

    $cartItem = CartItem::factory()->create(['cart_id' => $cart->id]);

    delete(route('cart.destroy', $cartItem->id))
        ->assertRedirect(route('products.index'));

    expect(CartItem::where('id', $cartItem->id)->exists())->toBeFalse();
});

it('can checkout', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $cart = Cart::factory()->create(['user_id' => $user->id]);

    CartItem::factory(3)->create(['cart_id' => $cart->id]);

    $totalAmount = 100.0;
    post(route('cart.checkout'), ['totalAmount' => $totalAmount])
        ->assertRedirect(route('products.index'));

    expect(Order::where('user_id', $user->id)->where('total_amount', $totalAmount)->exists())->toBeTrue();
});
