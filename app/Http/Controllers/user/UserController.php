<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $pendingCount = Task::whereNull('status')->count();
        $prosesCount = Task::where('status', 'Proses')->count();
        $selesaiCount = Task::where('status', 'Selesai')->count();

        return view('user.dashboard', compact('pendingCount', 'prosesCount', 'selesaiCount'));
    }
}
