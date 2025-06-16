<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Models\Product;
use App\Models\Models\Category;
use App\Models\Models\PhysicalData;
use App\Models\Models\DigitalData;
use App\Models\Order;
use App\Models\Models\OrderItem;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    //
    

    public function index()
{
    // dd(auth()->user(), auth()->id());
    $products = \DB::table('products')
        ->join('categories', 'products.category_id', '=', 'categories.id')
        ->leftJoin('physical_data', 'products.id', '=', 'physical_data.id')
        ->leftJoin('digital_data', 'products.id', '=', 'digital_data.id')
        ->select(
            'products.id',
            'products.name',
            'products.price',
            'categories.name as category',
            'products.type',
            \DB::raw("COALESCE(physical_data.weight, digital_data.filesize) as extra_info")
        )
        ->get();

    return view('products.index', compact('products'));
}

public function viewOrders()
{
    $orders = Order::with('user', 'items.product')->orderBy('created_at', 'desc')->get();
    return view('admin.orders', compact('orders'));
}
public function create()
{
    $categories = Category::all();
    return view('products.form', ['categories' => $categories, 'product' => null]);
}

public function store(Request $request)
{
    $request->validate([
        'name' => 'required',
        'price' => 'required|numeric',
        'category_id' => 'required|exists:categories,id',
        'type' => 'required|in:physical,digital',
        'weight' => 'required_if:type,physical|nullable|numeric',
        'filesize' => 'required_if:type,digital|nullable|numeric',
    ]);

    $product = Product::create($request->only(['name', 'price', 'category_id', 'type']));

    if ($request->type === 'physical') {
        PhysicalData::create(['id' => $product->id, 'weight' => $request->weight]);
    } else {
        DigitalData::create(['id' => $product->id, 'filesize' => $request->filesize]);
    }

    return redirect()->route('home')->with('success', 'Product added.');
}

public function edit($id)
{
    $product = Product::with('physicalData', 'digitalData')->findOrFail($id);
    $categories = Category::all();
    return view('products.form', compact('product', 'categories'));
}

public function update(Request $request, $id)
{
    $request->validate([
        'name' => 'required',
        'price' => 'required|numeric',
        'category_id' => 'required|exists:categories,id',
        'type' => 'required|in:physical,digital',
        'weight' => 'required_if:type,physical|nullable|numeric',
        'filesize' => 'required_if:type,digital|nullable|numeric',
    ]);

    $product = Product::findOrFail($id);
    $product->update($request->only(['name', 'price', 'category_id', 'type']));

    if ($request->type === 'physical') {
        $product->physicalData()->updateOrCreate(['id' => $product->id], ['weight' => $request->weight]);
        $product->digitalData()->delete();
    } else {
        $product->digitalData()->updateOrCreate(['id' => $product->id], ['filesize' => $request->filesize]);
        $product->physicalData()->delete();
    }

    return redirect()->route('home')->with('success', 'Product updated.');
}

public function destroy($id)
{
    $product = Product::findOrFail($id);
    $product->physicalData()->delete();
    $product->digitalData()->delete();
    $product->delete();
    return back()->with('success', 'Product deleted.');
}
public function productReport()
{
    // $user = Auth::user();
    // if (!$user || $user->role !== 'admin') {
    //     return redirect()->route('home')->with('error', 'Unauthorized');
    // }

    $report = \DB::table('products')
    ->join('order_items', 'products.id', '=', 'order_items.product_id')
    ->select('products.id', 'products.name', \DB::raw('SUM(order_items.quantity) as total_ordered'))
    ->groupBy('products.id', 'products.name')
    ->orderBy('total_ordered', 'desc')
    ->get();

    // $ordersByDate = \DB::table('orders')
    //     ->join('users', 'orders.user_id', '=', 'users.id')
    //     ->select('orders.*', 'users.username', \DB::raw('DATE(orders.created_at) as order_date'))
    //     ->orderBy('orders.created_at', 'desc')
    //     ->get()
    //     ->groupBy('order_date'); // Grouped by date
    $orderSummaries = \DB::table('orders')
        ->select(
            \DB::raw('DATE(created_at) as order_date'),
            \DB::raw('GROUP_CONCAT(id ORDER BY id ASC) as order_numbers'),
            \DB::raw('SUM(total_amount) as total_price')
        )
        ->groupBy(\DB::raw('DATE(created_at)'))
        ->orderByDesc('order_date')
        ->get();


    return view('admin.product_report', compact('report', 'orderSummaries'));
}


}
