<?php

use App\Models\Discount;
use Illuminate\Contracts\View\View;
use function Pest\Laravel\get;

it('displays a list of discounts', function () {
    $discounts = Discount::factory(3)->create();

    $response = get(route('discounts.index'))
        ->assertOk()
        ->assertViewIs('discount.index');

    expect($response->original)->toBeInstanceOf(View::class);

    foreach ($discounts as $discount) {
        expect($response->content())->toContain($discount->name);
    }
});
