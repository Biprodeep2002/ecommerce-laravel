<!DOCTYPE html>
<html>
<head>
    <title>Select Products</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }
        table {
            width: 90%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: center;
        }
        th {
            background-color: #f9f9f9;
        }
        button {
            padding: 6px 12px;
            cursor: pointer;
        }
        a {
            text-decoration: none;
            color: #007bff;
        }
    </style>
</head>
<body>
    <h2>Select a Product</h2>
    @php
        $user = Auth::user();
    @endphp

    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Price ($)</th>
                <th>Category</th>
                <th>Type</th>
                <th>Weight / File Size</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($products as $product)
                <tr>
                    <td>{{ $product->name }}</td>
                    <td>{{ number_format($product->price, 2) }}</td>
                    <td>{{ $product->category->name ?? 'N/A' }}</td>
                    <td>{{ ucfirst($product->type) }}</td>
                    <td>
                        @if ($product->type === 'physical')
                            {{ $product->physicalData->weight}} kg
                        @elseif ($product->type === 'digital')
                            {{ $product->digitalData->filesize}} MB
                        @else
                            -
                        @endif
                    </td>
                    
                    <td>
                        {{-- @if($user && $user->role === 'admin')
                            <a href="{{ route('admin.orders') }}">View Orders</a>
                        @endif --}}
                        <form action="{{ route('cart.add') }}" method="POST">
                            @csrf
                            <input type="hidden" name="id" value="{{ $product->id }}">
                            <button type="submit">Add to Cart</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">No products available.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <a href="{{ route('cart.view') }}"> Back to Cart</a>
</body>
</html>
