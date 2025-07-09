<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Cache;

class RestaurantController extends Controller
{
    public function index()
    {
        return view('restaurant.index', [
            'categories' => Cache::remember(config('default.catalogue_cache_key'), 3600, function () {
                return Category::with([
                    'items' => fn ($query) => $query->orderBy('sort_order')])
                        ->orderBy('sort_order')
                        ->get();
            })
        ]);
    }
}
