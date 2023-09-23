@extends('layouts.main')

@section('content')
    <div class="p-6">
        <h1>List of products</h1>
        <table>
            <thead>
            <tr>
                <th>ID</th>
                <th class="pl-6">Title</th>
                <th class="pl-6">Description</th>
                <th class="pl-6">Price</th>
                <th class="pl-6">Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach($products as $product)
                <tr>
                    <td>{{ $product->id }}</td>
                    <td class="pl-6">{{ $product->name }}</td>
                    <td class="pl-6">{{ $product->description }}</td>
                    <td class="pl-6">{{ $product->price }}</td>
                    <td class="pl-6">
                        <form action="{{ route('cart.add') }}" method="POST">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <button type="submit" class="ml-6 underline">Add to cart</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
