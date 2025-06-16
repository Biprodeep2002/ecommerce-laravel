<!DOCTYPE html>
<html>
<head>
    <title>All Orders - Admin Panel</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: center;
            vertical-align: top;
        }

        th {
            background-color: #f4f4f4;
        }

        ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        ul li {
            margin: 5px 0;
        }

        .back-link {
            display: inline-block;
            padding: 10px 20px;
            background-color: #555;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
        }

        .back-link:hover {
            background-color: #333;
        }
    </style>
</head>
<body>
    <a href="{{ route('home') }}" class="back-link">Back to Home</a>
    <a href="{{ route('admin.productReport') }}" class="back-link">Report</a>
    <h2>All Orders</h2>

    <table>
        <thead>
            <tr>
                <th>Order ID</th>
                <th>User</th>
                <th>Total Price</th>
                <th>Items</th>
                <th>Order Time</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($orders as $order)
                <tr>
                    <td>{{ $order->id }}</td>
                    <td>{{ $order->user->username ?? 'Unknown User' }}</td>
                    <td>${{ number_format($order->total_amount, 2) }}</td>
                    <td>
                        <ul>
                            @foreach ($order->items as $item)
                                <li>{{ $item->product->name }} (x{{ $item->quantity }})</li>
                            @endforeach
                        </ul>
                    </td>
                    <td>{{ $order->created_at->format('Y-m-d H:i') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">No orders found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>



</body>
</html>
