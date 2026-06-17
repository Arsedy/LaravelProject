<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(): View
    {
        return view('front.home');
    }

    public function store(Request $request): View
    {
        $categories = Category::where('status', true)->get();
        $query = Product::where('status', true)->with('category');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('keywords', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->input('category_id'));
        }

        if ($request->filled('price_min')) {
            $query->where('price', '>=', $request->input('price_min'));
        }

        if ($request->filled('price_max')) {
            $query->where('price', '<=', $request->input('price_max'));
        }

        $products = $query->paginate(9)->withQueryString();

        return view('front.store', compact('products', 'categories'));
    }

    public function product(Request $request): View
    {
        $productId = $request->query('product_id');
        $product = Product::find($productId) ?? Product::first();

        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->limit(4)
            ->get();

        return view('front.product', compact('product', 'relatedProducts'));
    }

    public function blank(): View
    {
        return view('front.blank');
    }
}
