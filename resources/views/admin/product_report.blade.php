<!DOCTYPE html>
<html>
<head>
    <title>Product Sales Report</title>
    <style>
        table {
            width: 80%;
            border-collapse: collapse;
            margin: 20px auto;
        }
        th, td {
            padding: 12px;
            border: 1px solid #ccc;
            text-align: center;
        }
        th {
            background-color: #eee;
        }
        h2 {
            text-align: center;
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
    <h2>Product Sales Report</h2>
    <table>
        <thead>
            <tr>
                <th>Product ID</th>
                <th>Name</th>
                <th>Total Ordered</th>
            </tr>
        </thead>
        <tbody>
            @forelse($report as $product)
                <tr>
                    <td>{{ $product->id }}</td>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->total_ordered ?? 0 }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3">No products found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <h2>Orders Grouped by Date</h2>

    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Order Numbers</th>
                <th>Total Price ($)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orderSummaries as $summary)
                <tr>
                    <td>{{ $summary->order_date }}</td>
                    <td>
                        @php
                            $orderIds = explode(',', $summary->order_numbers);
                            $formatted = collect($orderIds)->map(fn($id) => '#' . $id)->implode(', ');
                        @endphp
                        {{ $formatted }}
                    </td>
                    <td>${{ number_format($summary->total_price, 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3">No orders found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <a href="{{ route('admin.orders') }}" class="back-link">Order List</a>
</body>
</html>
