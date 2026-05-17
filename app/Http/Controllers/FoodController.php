<?php

namespace App\Http\Controllers;

use App\Models\Food;
use App\Models\Restaurant;
use Illuminate\Http\Request;

class FoodController extends Controller
{
    public function index()
    {
        $foods = Food::with('restaurant')->get();

        return view('admin.foods.index', compact('foods'));
    }

    public function create()
    {
        $restaurants = Restaurant::all();

        return view('admin.foods.create', compact('restaurants'));
    }

    public function store(Request $request)
    {
        Food::create([
            'restaurant_id' => $request->restaurant_id,
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
        ]);

        return redirect('/foods');
    }
}