<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Render checkout page with cart items.
     */
    public function checkout(): RedirectResponse|View
    {
        $cartItems = Cart::with('product')
            ->where('user_id', Auth::id())
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Your cart is empty.');
        }

        $subtotal = $cartItems->sum(function ($item) {
            return $item->price * $item->quantity;
        });

        $shippingPrice = 0;
        $total = $subtotal + $shippingPrice;

        return view('home.checkout', compact(
            'cartItems',
            'subtotal',
            'shippingPrice',
            'total'
        ));
    }

    /**
     * Store a newly placed order from database cart.
     */
    public function placeOrder(Request $request): RedirectResponse|JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:30',
            'address' => 'required|string',
            'city' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'zip_code' => 'nullable|string|max:50',
            'shipping_method' => 'required|string|max:255',
            'payment_method' => 'required|string|max:255',
        ]);

        $cartItems = Cart::with('product')
            ->where('user_id', Auth::id())
            ->get();

        if ($cartItems->isEmpty()) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Your cart is empty.'], 422);
            }

            return redirect()->route('cart.index')
                ->with('error', 'Your cart is empty.');
        }

        try {
            $order = DB::transaction(function () use ($request, $cartItems) {
                $subtotal = $cartItems->sum(function ($item) {
                    return $item->price * $item->quantity;
                });

                $shippingPrice = $request->shipping_method === 'Standard Shipping' ? 4 : 0;
                $total = $subtotal + $shippingPrice;

                $order = Order::create([
                    'user_id' => Auth::id(),
                    'name' => $request->name,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'address' => $request->address,
                    'city' => $request->city,
                    'country' => $request->country,
                    'zip_code' => $request->zip_code,
                    'subtotal' => $subtotal,
                    'shipping_price' => $shippingPrice,
                    'total' => $total,
                    'shipping_method' => $request->shipping_method,
                    'payment_method' => $request->payment_method,
                    'status' => 'New',
                ]);

                foreach ($cartItems as $item) {
                    $product = $item->product;
                    if ($product->stock < $item->quantity) {
                        throw new \Exception($product->title.' does not have enough stock.');
                    }

                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'product_title' => $product->title,
                        'price' => $item->price,
                        'quantity' => $item->quantity,
                        'total' => $item->price * $item->quantity,
                    ]);

                    $product->decrement('stock', $item->quantity);
                }

                // Clear database cart
                Cart::where('user_id', Auth::id())->delete();

                return $order;
            });

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'order_id' => $order->id,
                    'total' => number_format($order->total, 2),
                    'name' => $order->name,
                    'address' => $order->address,
                ]);
            }

            return redirect()->route('home')
                ->with('success', 'Your order has been placed successfully.');

        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json(['error' => $e->getMessage()], 422);
            }

            return back()->withInput()->with('error', $e->getMessage());
        }
    }
}
