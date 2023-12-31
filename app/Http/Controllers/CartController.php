<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\UpdateCartItemRequest;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Services\CartService;
use App\Services\CheckoutService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    private CartService $cartService;
    private CheckoutService $checkoutService;

    public function __construct(CartService $cartService, CheckoutService $orderService)
    {
        $this->cartService = $cartService;
        $this->checkoutService = $orderService;
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

    public function checkout(Request $request): RedirectResponse
    {
        $this->checkoutService->createOrder($request->totalAmount);

        return back()
            ->with('success', 'the order has been successfully created');
    }
}
