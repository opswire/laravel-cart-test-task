<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\UpdateCartItemRequest;
use App\Models\CartItem;
use App\Services\CartService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CartController extends Controller
{
    private CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function show(Request $request): View
    {
        $cart = $this->cartService->findCartOrCreate();

        $cartItems = $cart->items()->get();
        $totalAmount = $this->cartService->calculateTotalAmount($cartItems, $request->discount);

        return view('cart.index')
            ->with('cartItems', $cartItems)
            ->with('totalAmount', $totalAmount);
    }

    public function addItem(Request $request): RedirectResponse
    {
        $productId = $request->input('product_id');
        $this->cartService->saveCartItem($productId);

        return redirect()->route('cart.show');
    }

    public function updateQuantity(UpdateCartItemRequest $request, CartItem $item): RedirectResponse
    {
        $request->validated();

        $item->update(['quantity' => $request->quantity]);

        return back()
            ->with('success', 'The quantity of the product has been successfully updated');
    }

    public function destroy(CartItem $item): RedirectResponse
    {
        $item->delete();

        return back()
            ->with('success', 'The product has been successfully removed from the cart');
    }

    public function checkout()
    {
        // TODO: checkout
    }
}
