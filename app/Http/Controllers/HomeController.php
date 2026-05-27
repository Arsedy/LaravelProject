<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $title = 'Home Page';
        $message = 'Welcome to the home page!';
        return view('front.home', compact('title', 'message'));
    }
}