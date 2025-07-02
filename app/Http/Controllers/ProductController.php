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
use Illuminate\Support\Arr;
use App\Http\Requests\StoreProductRequest;

class ProductController extends Controller
{
    public function index()
{
    $products = Product::with(['category', 'physicalData', 'digitalData'])
    ->get()
    ->map(function($product) {
        return [
            'id' => $product->id,
            'name' => $product->name,
            'price' => $product->price,
            'category' => $product->category->name ?? null,
            'type' => $product->type,
            'extra_info' => $product->type === 'physical'
                ? $product->physicalData->weight ?? null
                : $product->digitalData->filesize ?? null,
        ];
    });

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

public function store(StoreProductRequest $request)
{
    $validated=$request->validated();

    $product = Product::create(Arr::only($validated, ['name', 'price', 'category_id', 'type']));

    if ($validated['type'] === 'physical') {
        $product->physicaldata()->create(['weight' => $validated['weight']]);
    } else {
        $product->digitaldata(['filesize' => $validated['filesize']]);
    }

    return redirect()->route('home')->with('success', 'Product added.');
}

public function edit($id)
{
    $product = Product::with('physicalData', 'digitalData')->findOrFail($id);
    $categories = Category::all();
    return view('products.form', compact('product', 'categories'));
}

public function update(StoreProductRequest $request, $id)
{
    $validated=$request->validated();

    $product = Product::findOrFail($id);
    $product->update(Arr::only($validated, ['name', 'price', 'category_id', 'type']));

    if ($validated['type'] === 'physical') {
        $product->physicalData()->updateOrCreate(['id' => $product->id], ['weight' => $validated['weight']]);
        $product->digitalData()->delete();
    } else {
        $product->digitalData()->updateOrCreate(['id' => $product->id], ['filesize' => $validated['filesize']]);
        $product->physicalData()->delete();
    }

    return redirect()->route('home')->with('success', 'Product updated.');
}

public function destroy($id)
{
    $product = Product::findOrFail($id);
    $product->delete();
    return back()->with('success', 'Product deleted.');
}
public function productReport()
{

    $report = Product::withSum('orderItems as total_ordered', 'quantity')
    ->orderByDesc('total_ordered')
    ->get(['id', 'name']);

    $orderSummaries = Order::selectRaw('DATE(created_at) as order_date')
    ->selectRaw('GROUP_CONCAT(id ORDER BY id ASC) as order_numbers')
    ->selectRaw('SUM(total_amount) as total_price')
    ->groupByRaw('DATE(created_at)')
    ->orderByDesc('order_date')
    ->get();


    return view('admin.product_report', compact('report', 'orderSummaries'));
}


}
