@extends('layouts.main')

@section('content')
    <div class="p-6">
        <h1>Cart</h1>
        <hr>
        @if(session('success'))
            <div class="text-green-700 font-semibold">
                {{ session('success') }}
            </div>
        @endif
        @if($errors->any())
            <span class="text-red-700 font-semibold">{{ implode('', $errors->all()) }}</span>
        @endif
        <table>
            <thead>
            <tr>
                <th>ID</th>
                <th class="pl-6">Title</th>
                <th class="pl-10">Description</th>
                <th class="pl-16">Price</th>
                <th class="pl-16">Quantity</th>
                <th class="pl-16">Update Quantity</th>
                <th class="pl-16">Delete Item</th>
            </tr>
            </thead>
            <tbody>
            @foreach($cartItems as $item)
                <tr>
                    <td>{{ $item->product->id }}</td>
                    <td class="pl-6">{{ $item->product->name }}</td>
                    <td class="pl-10">{{ $item->product->description }}</td>
                    <td class="pl-16">{{ $item->product->price }}</td>
                    <td class="pl-16">{{ $item->quantity }}</td>
                    <td class="pl-16">
                        <form action="{{ route('cart.update', ['item' => $item]) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <label> Quantity:
                                <input type="number" name="quantity" value="{{ $item->quantity }}">
                            </label>
                            <button class="underline" type="submit">Update</button>
                        </form>
                    </td>
                    <td class="pl-16">
                        <form action="{{ route('cart.destroy', ['item' => $item]) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button class="underline" type="submit">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <hr>
        <div class="text-purple-900 font-semibold pt-4 pb-4">
            Total Amount: {{ $totalAmount }}
        </div>
        <form action="{{ route('cart.checkout') }}" method="POST">
            <button class="underline font-bold">Checkout</button>
        </form>
    </div>
@endsection

