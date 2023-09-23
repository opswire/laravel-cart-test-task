<?php
declare(strict_types=1);

namespace App\Services;

use App\Models\Discount;
use Illuminate\Database\Eloquent\Collection;

class DiscountService
{
    public function calculateTotalAmountWithDiscounts(Collection $cartItems, float $totalAmount, string $discountName): float
    {
        $cartItems->old_price = $totalAmount;

        $discount = Discount::firstWhere('name', $discountName);

        switch ($discount->type) {
            case Discount::TYPE_PRODUCT_DISCOUNT:
                $totalAmount = $this->calculateProductDiscount($cartItems, $totalAmount, $discount);
                break;
            case Discount::TYPE_TOTAL_DISCOUNT:
                $totalAmount = $this->calculateTotalDiscount($totalAmount, $discount);
                break;
            case Discount::TYPE_COMBINED_DISCOUNT: // TODO: применять скидку ко всему заказу или только к товару?
                $totalAmount = $this->calculateCombinedDiscount($cartItems, $totalAmount, $discount);
                break;
        }

        return $totalAmount;
    }

    private function calculateProductDiscount(Collection $cartItems, float $totalAmount, Discount $discount): float
    {
        $productId = $discount->product_id;
        $discountPercent = $discount->discount_percent;

        if ($cartItems->contains('product_id', $productId)) {
            $productItem = $cartItems->firstWhere('product_id', $productId);
            $totalAmount -= $productItem->product->price * ($discountPercent / 100);
            $productItem->new_price = $productItem->product->price * ((100 - $discountPercent) / 100);
        }

        return $totalAmount;
    }

    private function calculateTotalDiscount(float $totalAmount, Discount $discount): float
    {
        if ($discount->minimum_order_amount <= $totalAmount) {
            $totalAmount = $totalAmount * ((100 - $discount->discount_percent) / 100);
        }

        return $totalAmount;
    }

    private function calculateCombinedDiscount(Collection $cartItems, float $totalAmount, Discount $discount): float
    {
        if ($cartItems->contains('product_id', $discount->product_id) &&
            $discount->minimum_order_amount <= $totalAmount) {
            $totalAmount = $totalAmount * ((100 - $discount->discount_percent) / 100);
        }

        return $totalAmount;
    }
}
