<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        return view('front.home');
    }

    public function store(): View
    {
        return view('front.store');
    }

    public function product(): View
    {
        return view('front.product');
    }

    public function checkout(): View
    {
        return view('front.checkout');
    }

    public function blank(): View
    {
        return view('front.blank');
    }

    public function admin(): View
    {
        return view('admin.home');
    }
}
