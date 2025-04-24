<?php

namespace App\Http\Controllers;

use App\Models\User;

class TaskStatisticsController extends Controller
{
    /**
     * Display task statistics for all users.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show()
    {
        $users = User::withCount([
            'tasks as total_tasks',
            'tasks as completed_tasks' => fn($query) => $query->where('status', 'completed'),
            'tasks as pending_tasks' => fn($query) => $query->where('status', 'pending'),
        ])->get(['id', 'name', 'email']);

        return response()->json($users);
    }
}