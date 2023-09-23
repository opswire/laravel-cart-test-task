<?php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    const TYPE_PRODUCT_DISCOUNT = 'product';
    const TYPE_TOTAL_DISCOUNT = 'total';
    const TYPE_COMBINED_DISCOUNT = 'combined';

    public static function getDiscountTypes(): array
    {
        return [
            self::TYPE_PRODUCT_DISCOUNT,
            self::TYPE_TOTAL_DISCOUNT,
            self::TYPE_COMBINED_DISCOUNT,
        ];
    }

    use HasFactory;
}
