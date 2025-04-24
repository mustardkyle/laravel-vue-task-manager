<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserTaskController extends Controller
{
    /**
     * Display a listing of users with their task statistics.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        // Fetch users with their tasks in a single query
        $users = User::with('tasks')->get();

        // Transform users with task statistics
        $usersWithStats = $users->map(fn($user) => [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'stats' => [
                'completed' => $user->tasks->where('status', 'completed')->count(),
                'pending' => $user->tasks->where('status', 'pending')->count(),
                'total' => $user->tasks->count(),
            ],
            'tasks' => $user->tasks,
        ]);

        return response()->json($usersWithStats);
    }
}