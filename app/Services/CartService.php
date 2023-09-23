<?php
declare(strict_types=1);

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;
use Ramsey\Uuid\UuidInterface;

class CartService
{
    const SESSION_COLUMN_TITLE = 'session_id';
    const SESSION_EXPIRES_AT = 60 * 24 * 30;

    private DiscountService $discountService;

    public function __construct(DiscountService $discountService)
    {
        $this->discountService = $discountService;
    }

    public function findCartOrCreate(): Cart
    {
        $sessionId = $this->getSessionIdByCookie();
        $user = Auth::user();

        if (Auth::check()) {
            $cart = Cart::where('user_id', $user->id)->first();
            if (!$cart) {
                $cart = Cart::firstOrCreate(
                    [self::SESSION_COLUMN_TITLE => $sessionId],
                    ['user_id' => $user->id],
                );
            }
        } else {
            $cart = Cart::firstOrCreate([self::SESSION_COLUMN_TITLE => $sessionId]);
        }

        return $cart;
    }

    private function getSessionIdByCookie(): UuidInterface|string
    {
        $sessionId = Cookie::get(self::SESSION_COLUMN_TITLE);

        if (!$sessionId) {
            $sessionId = Str::uuid();
            Cookie::queue(self::SESSION_COLUMN_TITLE, $sessionId, self::SESSION_EXPIRES_AT);
        }

        return $sessionId;
    }

    public function calculateTotalAmount(Collection $cartItems, string|null $discountName): float
    {
        $totalAmount = 0;

        foreach ($cartItems as $cartItem) {
            $totalAmount += $cartItem->product->price * $cartItem->quantity;
        }

        if(!empty($discountName)) {
            $totalAmount = $this->discountService->calculateTotalAmountWithDiscounts($cartItems, $totalAmount, $discountName);
        }

        return $totalAmount;
    }

    public function saveCartItem($productId): void
    {
        $product = Product::findOrFail($productId);

        $cart = $this->findCartOrCreate();

        $cartItem = $cart->items()->where(['product_id' => $product->id])->first();

        if ($cartItem) {
            $cartItem->update(['quantity' => $cartItem->quantity + 1]);
        } else {
            $cartItem = new CartItem([
                'product_id' => $product->id,
                'quantity'   => 1,
            ]);
            $cart->items()->save($cartItem);
        }
    }
}
