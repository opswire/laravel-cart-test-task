<?php
declare(strict_types=1);

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

// TODO: Логика по обработке заказа
class CheckoutService
{
    public function createOrder($totalAmount): void
    {
        $user = Auth::user();

        DB::transaction(function () use ($user, $totalAmount) {
            $order = Order::create([
                'user_id'      => $user->id,
                'total_amount' => $totalAmount,
            ]);

            $cart = $user->cart()->first();

            foreach ($cart->items as $item) {
                OrderItem::create([
                    'order_id'        => $order->id,
                    'product_id'      => $item->product_id,
                    'quantity'        => $item->quantity,
                    'discount_amount' => $item->new_price ? $item->price - $item->new_price : null,
                ]);
                $item->delete();
            }

            $cart->items()->delete();
        });
    }
}
