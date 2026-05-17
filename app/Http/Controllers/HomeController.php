<?php

namespace App\Http\Controllers;

use App\Models\Food;

class HomeController extends Controller
{
    public function index()
    {
        $foods = Food::with('restaurant')->get();

        return view('home', compact('foods'));
    }
}