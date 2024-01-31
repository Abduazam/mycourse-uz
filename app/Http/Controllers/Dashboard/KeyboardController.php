<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\HomeController;
use App\Models\Keyboard;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;

class KeyboardController extends HomeController
{
    public function index(): Renderable
    {
        $keyboards = Keyboard::all();
        return view('dashboard.keyboards.index', compact('keyboards'));
    }
}
