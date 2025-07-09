<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class RestaurantController extends Controller
{
    public function index()
    {
        $categories = Category::with(['items' => function ($query) {
            $query->orderBy('sort_order');
        }])
        ->orderBy('sort_order')
        ->get();

        return view('restaurant.index', compact('categories'));
    }
}
