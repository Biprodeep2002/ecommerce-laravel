<!DOCTYPE html>
<html>
<head>
    <title>Product Sales Report</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>

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
        canvas {
            border: 1px solid red;
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

    <div class="chart-container">
        <h2 style="text-align:center;">Order Price by Date (Chart)</h2>
        <canvas id="ordersChart" height="100"></canvas>
    </div>
    
    <a href="{{ route('admin.orders') }}" class="back-link">Order List</a>
    
    {{-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> --}}
    
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('ordersChart')?.getContext('2d');
            if (!ctx) {
                console.error('Canvas not found!');
                return;
            }
    
            const labels = {!! json_encode($orderSummaries->pluck('order_date')) !!};
            const data = {!! json_encode($orderSummaries->pluck('total_price')->map(fn($v) => (float) $v)) !!};
    
            new window.Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Total Price ($)',
                        data: data,
                        backgroundColor: 'rgba(75, 192, 192, 0.5)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return '$' + context.formattedValue;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: 'Order Date'
                            }
                        },
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Total Price ($)'
                            }
                        }
                    }
                }
            });
        });
    </script>
    
</body>
</html>
