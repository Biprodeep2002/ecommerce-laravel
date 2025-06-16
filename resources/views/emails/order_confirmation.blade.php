<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Order Confirmation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .email-container {
            width: 90%;
            max-width: 600px;
            margin: auto;
            border: 1px solid #ddd;
            padding: 20px;
            background-color: #fafafa;
        }
        h2 {
            color: #2c3e50;
        }
        .order-details {
            margin-top: 20px;
        }
        .order-details ul {
            padding-left: 20px;
        }
        .order-details li {
            margin-bottom: 8px;
        }
        .total {
            margin-top: 20px;
            font-size: 18px;
            font-weight: bold;
            color: #000;
        }
        .footer {
            margin-top: 30px;
            font-size: 14px;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <h2>Hi {{ $username }},</h2>

        <p>Your order (ID: <strong>{{ $orderId }}</strong>) has been placed successfully!</p>

        <div class="order-details">
            <h3>Order Details:</h3>
            <ul>
                @foreach ($cart as $item)
                    <li>
                        {{ $item['name'] }} &mdash; Qty: {{ $item['quantity'] }} &mdash; ${{ number_format($item['price'] * $item['quantity'], 2) }}
                    </li>
                @endforeach
            </ul>
        </div>

        <p class="total">Total: ${{ number_format($total, 2) }}</p>

        <p>Thank you for shopping with us!</p>

        <div class="footer">
            <p>&copy; {{ date('Y') }} Your Shop. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
