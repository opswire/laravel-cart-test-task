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
                        <a href="" class="ml-6 underline">Add to cart</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
