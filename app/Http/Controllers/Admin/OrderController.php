<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $status = $request->input('status');

        $orders = Order::query()
            ->with(['user', 'items'])
            ->when($status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $statuses = Order::STATUSES;

        return view('admin.orders.index', compact('orders', 'statuses', 'status'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order): View
    {
        $order->load(['items.product', 'user']);
        $statuses = Order::STATUSES;

        return view('admin.orders.show', compact('order', 'statuses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'string', 'in:'.implode(',', Order::STATUSES)],
        ]);

        $order->update($validated);

        return redirect()
            ->route('admin.orders.show', $order)
            ->with('success', 'Order status updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order): RedirectResponse
    {
        $order->delete();

        return redirect()
            ->route('admin.orders.index')
            ->with('success', 'Order deleted successfully.');
    }
}
