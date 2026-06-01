<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(): View
    {
        return view('front.home');
    }

    public function store(): View
    {
        $products = Product::where('status', true)->with('category')->paginate(9);

        return view('front.store', compact('products'));
    }

    public function product(Request $request): View
    {
        $productId = $request->query('product_id');
        $product = Product::find($productId) ?? Product::first();

        return view('front.product', compact('product'));
    }

    public function checkout(Request $request): View
    {
        $productId = $request->query('product_id');
        $quantity = (int) $request->query('quantity', 1);
        if ($quantity < 1) {
            $quantity = 1;
        }

        // Retrieve product or fallback to the first active/existing product
        $product = Product::find($productId) ?? Product::first();

        return view('front.checkout', compact('product', 'quantity'));
    }

    public function blank(): View
    {
        return view('front.blank');
    }
}
