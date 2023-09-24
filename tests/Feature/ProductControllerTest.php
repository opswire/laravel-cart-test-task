<?php

use App\Models\Product;
use function Pest\Laravel\get;

test('index page displays products', function () {
    Product::factory(3)->create();

    $response = get(route('products.index'));

    $response->assertStatus(200);

    $products = Product::all();
    foreach ($products as $product) {
        expect($response->content())->toContain($product->name);
    }
});
