<!DOCTYPE html>
<html>
<head>
    <title>Payment Successful</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 40px;
            text-align: center;
        }
        h2 {
            color: green;
        }
        a {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 15px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        a:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <h2>Payment Successful</h2>
    <p>Your order has been placed. Thank you for shopping!</p>
    <a href="{{ route('home') }}">Back to Products</a>
</body>
</html>
