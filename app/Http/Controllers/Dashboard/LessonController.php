<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use Illuminate\View\View;

class LessonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('dashboard.lessons.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('dashboard.lessons.create');
    }

    /**
     * Display the specified resource.
     */
    public function show(Lesson $lesson): View
    {
        return view('dashboard.lessons.show', compact('lesson'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Lesson $lesson): View
    {
        return view('dashboard.lessons.edit', compact('lesson'));
    }
}
