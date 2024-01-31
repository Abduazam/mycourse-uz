<?php

namespace App\Http\Controllers;

use App\Models\BotUser;
use App\Models\UserCourse;
use App\Models\UserTask;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(): Renderable
    {
        $users = BotUser::query()->count();
        $students = UserCourse::query()->select('user_id')->where('status', 1)->groupBy('user_id')->get();
        $applications = UserCourse::query()->where('status', 0)->groupBy('user_id')->count();
        $user_tasks = UserTask::query()->where('status', 1)->count();
        return view('dashboard.home', compact('users', 'students', 'applications', 'user_tasks'));
    }
}
