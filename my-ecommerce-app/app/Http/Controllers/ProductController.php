<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
{
    // OLD:
    // $products = Product::latest()->paginate(10); 

    // NEW (Eager Loading):
    $products = Product::with('category')->latest()->paginate(10);

    return view('products.index', compact('products'));
}


    /**
     * Show the form for creating a new resource.
     */
    public function create()
{
    $categories = Category::all(); // Get all categories
    return view('products.create', compact('categories')); // Pass categories to the view
}


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate input
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ]);

        // Create product
        Product::create($request->all());

        return redirect()->route('products.index')
                         ->with('success', 'Product created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
{
    $categories = Category::all(); // Get all categories
    return view('products.edit', compact('product', 'categories')); // Pass both product and categories to the view
}


    /**
     * Update the specified resource in storage.
     */
   public function update(Request $request, Product $product)
{
    // 1. Validate
    $request->validate([
        'name' => 'required|string|max:255',
        'category_id' => 'nullable|exists:categories,id',
        'price' => 'required|numeric|min:0',
        'stock' => 'required|integer|min:0',
        'description' => 'nullable|string',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
    ]);

    $data = $request->except('image');

    // 2. Handle File Upload
    if ($request->hasFile('image')) {
        // Delete old image if it exists
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        // Store new image
        $path = $request->file('image')->store('products', 'public');
        $data['image'] = $path;
    }

    // 3. Update Product
    $product->update($data);

    // 4. Redirect
    return redirect()->route('admin.products.index')
                     ->with('success', 'Product updated successfully.');
}

    /**
     * Remove the specified resource from storage.
     */
   public function destroy(Product $product)
{
    // Delete the image from storage
    if ($product->image) {
        Storage::disk('public')->delete($product->image);
    }

    // Delete the product from the database
    $product->delete();

    return redirect()->route('admin.products.index')
                     ->with('success', 'Product deleted successfully.');
}

    public function shop()
    {
    // We eager load 'category' to show it on the shop page
    $products = Product::with('category')->latest()->paginate(12);

    return view('shop.index', compact('products'));

    }
}
