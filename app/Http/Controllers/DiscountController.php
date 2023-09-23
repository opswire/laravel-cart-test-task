<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Discount;
use Illuminate\Contracts\View\View;

class DiscountController extends Controller
{
    public function index(): View
    {
        $discounts = Discount::all();

        return view('discount.index')
            ->with('discounts', $discounts);
    }
}
