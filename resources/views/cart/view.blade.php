<!DOCTYPE html>
<html>
<head>
    <title>Your Cart</title>
    <style>
        table {
            border-collapse: collapse;
            width: 80%;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #f3f3f3;
        }
        button {
            padding: 8px 16px;
            margin-right: 10px;
        }
    </style>
</head>
<body>
    <h2>Your Cart</h2>

    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Price ($)</th>
                <th>Qty</th>
                <th>Subtotal ($)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($cart as $item)
                <tr>
                    <td>{{ $item['name'] }}</td>
                    <td>{{ $item['price'] }}</td>
                    <td>{{ $item['quantity'] }}</td>
                    <td>{{ $item['price'] * $item['quantity'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <p><strong>Total: ${{ $total }}</strong></p>

    {{-- <form action="{{ route('checkout') }}" method="POST" style="display:inline-block;">
        @csrf
        <button type="submit">Checkout</button>
    </form> --}}

    <form action="{{ route('cart.select') }}" method="GET" style="display:inline-block;">
        <button type="submit">Continue Shopping</button>
    </form>

    <form action="{{ route('cart.clear') }}" method="POST" style="display:inline-block;">
        @csrf
        <button type="submit">Clear Cart</button>
    </form>
    
    <form action="{{ route('checkout') }}" method="POST">
        @csrf
        <br>
        <div>
            <label for="email">Enter Email for Order Confirmation:</label>
            <input type="email" name="email" id="email" required value="{{ old('email') }}">
        </div>
        <br>
        <button type="submit">Checkout</button>
    </form>
</body>
</html>
