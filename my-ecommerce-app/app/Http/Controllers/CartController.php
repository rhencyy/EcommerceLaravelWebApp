<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Gloudemans\Shoppingcart\Facades\Cart; // <-- Use this Facade

class CartController extends Controller
{
    public function store(Request $request)
    {
        // Find the product by its ID
        $product = Product::findOrFail($request->input('product_id'));

        // Get the quantity from the form, default to 1
        $quantity = $request->input('quantity', 1);

        // Check if there is enough stock
        if ($product->stock < $quantity) {
            return redirect()->back()->with('error', 'Not enough stock available!');
        }

        // Add the product to the cart
        Cart::add(
            $product->id,
            $product->name,
            $quantity,
            $product->price,
            ['image' => 'default.jpg'] // Add real images later
        );

        // Redirect back to the shop page with a success message
        return redirect()->route('shop.index')->with('success', 'Product added to cart!');
    }

    public function index()
    {
        $cartItems = Cart::content(); // Get all items in the cart
        $cartTotal = Cart::total();  // Get the total amount
        $cartCount = Cart::count();  // Get the count of items in the cart

        return view('cart.index', compact('cartItems', 'cartTotal', 'cartCount'));
    }

    public function destroy($rowId)
    {
        // Remove an item from the cart by its rowId
        Cart::remove($rowId);

        // Redirect back to the cart with a success message
        return redirect()->route('cart.index')->with('success', 'Item removed from cart!');
    }

    public function clear()
    {
        // Clear all items from the cart
        Cart::destroy();

        // Redirect back to the cart with a success message
        return redirect()->route('cart.index')->with('success', 'Cart cleared!');
    }
}
