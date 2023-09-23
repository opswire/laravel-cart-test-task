@php use App\Models\Discount; @endphp
@extends('layouts.main')

@section('content')
    <div class="p-6">
        <h1>List of discounts</h1>
        <table>
            <thead>
            <tr>
                <th>ID</th>
                <th class="pl-6">Name</th>
                <th class="pl-6">Type</th>
                <th class="pl-6">Product Id</th>
                <th class="pl-6">Minimum Order Amount</th>
                <th class="pl-6">Discount Percent</th>
            </tr>
            </thead>
            <tbody>
            @foreach($discounts as $discount)
                <tr>
                    <td>{{ $discount->id }}</td>
                    <td class="pl-6">{{ $discount->name }}</td>
                    <td class="pl-6">{{ $discount->type }}</td>
                    <td class="pl-6">
                        @if($discount->type != Discount::TYPE_TOTAL_DISCOUNT)
                            {{ $discount->product_id }}
                        @endif
                    </td>
                    <td class="pl-6">
                        @if($discount->type != Discount::TYPE_PRODUCT_DISCOUNT)
                            {{ $discount->minimum_order_amount }}
                        @endif
                    </td>
                    <td class="pl-6">{{ $discount->discount_percent }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
