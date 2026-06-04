<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index(): View
    {
        $cartItems = Cart::with('product')
            ->where('user_id', Auth::id())
            ->get();

        return view('home.cart', compact('cartItems'));
    }

    public function add(Request $request, $productId): RedirectResponse
    {
        $request->validate([
            'quantity' => 'nullable|integer|min:1',
        ]);

        $product = Product::findOrFail($productId);
        $quantity = (int) ($request->quantity ?? 1);

        if ($product->stock < $quantity) {
            return back()->with('error', 'Not enough stock available.');
        }

        $cart = Cart::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->first();

        // Calculate unit price after discount
        $price = $product->price;
        if ($product->discount > 0) {
            $price = $product->price - ($product->price * ($product->discount / 100));
        }

        if ($cart) {
            $newQuantity = $cart->quantity + $quantity;
            if ($product->stock < $newQuantity) {
                return back()->with('error', 'Not enough stock available.');
            }
            $cart->update([
                'quantity' => $newQuantity,
                'price' => $price,
            ]);
        } else {
            Cart::create([
                'user_id' => Auth::id(),
                'product_id' => $product->id,
                'quantity' => $quantity,
                'price' => $price,
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Product added to cart.');
    }

    public function update(Request $request, $cartId): RedirectResponse
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = Cart::where('user_id', Auth::id())
            ->findOrFail($cartId);

        if ($cart->product->stock < $request->quantity) {
            return back()->with('error', 'Not enough stock available.');
        }

        $cart->update([
            'quantity' => $request->quantity,
        ]);

        return back()->with('success', 'Cart updated.');
    }

    public function remove($cartId): RedirectResponse
    {
        $cart = Cart::where('user_id', Auth::id())
            ->findOrFail($cartId);

        $cart->delete();

        return back()->with('success', 'Product removed from cart.');
    }
}
