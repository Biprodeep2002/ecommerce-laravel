{{-- <!DOCTYPE html>
<html>
<head>
    <title>Product List</title>
    <style>
        table {
            border-collapse: collapse;
            width: 90%;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #eee;
        }
        select, button {
            padding: 8px 12px;
            margin-right: 10px;
        }
    </style>
</head>
<body>
    <h2>Product List</h2>
    @php
        $user = Auth::user();
    @endphp
    @if ($user && $user->role === 'admin')
        <a href="{{ route('products.create') }}">Add New Product</a>
        <a href="{{ route('admin.orders') }}" style="margin-left: 20px;">View All Orders</a>
    @endif

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Price (₹)</th>
                <th>Category</th>
                <th>Type</th>
                <th>Weight/FileSize</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $p)
                <tr>
                    <td>{{ $p->id }}</td>
                    <td>{{ $p->name }}</td>
                    <td>{{ $p->price }}</td>
                    <td>{{ $p->category }}</td>
                    <td>{{ ucfirst($p->type) }}</td>
                    <td>{{ $p->extra_info }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <form action="{{ route('cart.add') }}" method="POST">
        @csrf
        <label for="product_id">Select a product:</label>
        <select name="product_id" id="product_id">
            @foreach($products as $p)
                <option value="{{ $p->id }}">{{ $p->name }}</option>
            @endforeach
        </select>
        <button type="submit">Add to Cart</button>
    </form>

    <br><br>

    <a href="{{ route('cart.view') }}">
        <button>Go to Cart</button>
    </a>

    <form action="{{ route('logout') }}" method="POST" style="display:inline;">
        @csrf
        <button type="submit">Logout</button>
    </form>
</body>
</html> --}}

<!DOCTYPE html>
<html>
<head>
    <title>Product List</title>
    <style>
        table {
            border-collapse: collapse;
            width: 90%;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #eee;
        }
        select, button {
            padding: 8px 12px;
            margin-right: 10px;
        }
    </style>
</head>
<body>
    <h2>Product List</h2>
    {{-- <pre>
        Session: {{ print_r(session()->all(), true) }}
        User: {{ print_r(Auth::user(), true) }}
    </pre>
    @auth
        <p>Logged in as: {{ Auth::user()->username }} ({{ Auth::user()->role }})</p>
    @else
        <p>No user is logged in.</p>
    @endauth --}}
    @php
        use Illuminate\Support\Facades\Auth;
        $user = Auth::user();
    @endphp
    {{-- @if (Auth::check())
        <p>Welcome, {{ Auth::user()->username }} ({{ Auth::user()->role }})</p>
    @else
        <p>No user is logged in.</p>
    @endif --}}
    @if ($user && $user->role==='admin')
        <a href="{{ route('admin.orders') }}">
            <button>All Orders</button>
        </a>
    @endif
    <br><br>
    @auth
    @if ($user && $user->role === 'admin')
        <div style="margin-bottom: 20px;">
            <a href="{{ route('products.create') }}">
                <button>Add New Product</button>
            </a>
            <a href="{{ route('admin.orders') }}">
                <button>View All Orders</button>
            </a>
        </div>
    @endif
    @endauth
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Price ($)</th>
                <th>Category</th>
                <th>Type</th>
                <th>Weight/FileSize</th>
                @if ($user && $user->role === 'admin')
                    <th>Actions</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach($products as $p)
                <tr>
                    <td>{{ $p['id'] }}</td>
                    <td>{{ $p['name'] }}</td>
                    <td>{{ number_format($p['price'], 2) }}</td>
                    <td>{{ $p['category'] }}</td>
                    <td>{{ ucfirst($p['type']) }}</td>
                    <td>{{ $p['extra_info'] }}</td>
                    @if ($user && $user->role === 'admin')
                        <td>
                            <form action="{{ route('products.edit', $p['id']) }}" method="GET" style="display:inline;">
                                <button type="submit">Edit</button>
                            </form>
                            <form action="{{ route('products.destroy', $p['id']) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Delete this product?')">Delete</button>
                            </form>
                        </td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>

    @if (!$user || $user->role !== 'admin')
        <form action="{{ route('cart.add') }}" method="POST">
            @csrf
            <label for="product_id">Select a product:</label>
            <select name="product_id" id="product_id">
                @foreach($products as $p)
                    <option value="{{ $p['id'] }}">{{ $p['name'] }}</option>
                @endforeach
            </select>
            <button type="submit">Add to Cart</button>
        </form>

        <br><br>

        <a href="{{ route('cart.view') }}">
            <button>Go to Cart</button>
        </a>
    @endif

    <form action="{{ route('logout') }}" method="POST" style="display:inline; margin-top: 20px;">
        @csrf
        <button type="submit">Logout</button>
    </form>
</body>
</html>

