<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Store a newly created order in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'address' => ['required', 'string', 'max:500'],
            'telephone' => ['required', 'string', 'max:20'],
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $product = Product::findOrFail($validated['product_id']);

        // Check stock availability
        if ($product->stock < $validated['quantity']) {
            return back()
                ->withInput()
                ->with('error', "Sorry, only {$product->stock} units of {$product->title} are left in stock.");
        }

        // Calculate unit price after discount
        $unitPrice = $product->price;
        if ($product->discount > 0) {
            $unitPrice = $product->price - ($product->price * ($product->discount / 100));
        }
        $total = $unitPrice * $validated['quantity'];

        // Place order in transaction
        $order = DB::transaction(function () use ($validated, $product, $total) {
            // Decrement product stock
            $product->decrement('stock', $validated['quantity']);

            // Save order record
            return Order::create([
                'user_id' => Auth::check() ? Auth::id() : null,
                'product_id' => $product->id,
                'name' => $validated['name'],
                'email' => $validated['email'],
                'address' => $validated['address'],
                'telephone' => $validated['telephone'],
                'quantity' => $validated['quantity'],
                'total' => $total,
                'status' => 'pending',
            ]);
        });

        return redirect()
            ->route('home')
            ->with('success', "Thank you! Your order #{$order->id} has been placed successfully.");
    }
}
