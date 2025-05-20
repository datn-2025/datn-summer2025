<?php
namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    //dau noi di
    {
        $products=Product::with('category')->latest()->take(8)->get();
        $categories = Category::whereHas('products')->with('products')->get();
        return view('clients.home', compact('products', 'categories'));
    }
}