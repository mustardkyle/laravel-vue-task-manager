<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Models\Task;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $cacheKey = $this->generateCacheKey($request);

        $tasks = cache()->remember($cacheKey, 60, function () use ($request) {
            $query = Task::query();

            if ($request->has('status')) {
                $query->where('status', $request->status);
            }

            if ($request->has('priority')) {
                $query->where('priority', $request->priority);
            }

            if ($request->has('user_id')) {
                $query->where('user_id', $request->user_id);
            }

            return $query->orderBy('order')->get();
        });

        return response()->json($tasks);
    }

    public function show($id)
    {
        $task = Task::find($id);

        if (!$task) {
            return $this->notFoundResponse($id);
        }

        return response()->json($task);
    }

    public function store(Request $request)
    {
        $validated = $this->validateTask($request);

        $validated['user_id'] = $request->user()->id;
        $validated['order'] = Task::where('user_id', $validated['user_id'])->max('order') + 1;

        $task = Task::create($validated);

        event(new \App\Events\TaskUpdated($task));
        $this->clearTaskCache();

        return response()->json($task, 201);
    }

    public function reorder(Request $request)
    {
        $validated = $request->validate([
            'tasks' => 'required|array',
            'tasks.*' => 'integer|exists:tasks,id',
        ]);

        foreach ($validated['tasks'] as $order => $taskId) {
            Task::where('id', $taskId)->update(['order' => $order]);
        }

        $this->clearTaskCache();

        return response()->json(['message' => 'Tasks reordered successfully']);
    }

    public function update(Request $request, $id)
    {
        $task = Task::find($id);

        if (!$task) {
            return $this->notFoundResponse($id);
        }

        $validated = $this->validateTask($request, true);

        $task->update($validated);

        event(new \App\Events\TaskUpdated($task));
        $this->clearTaskCache();

        return response()->json($task);
    }

    public function destroy($id)
    {
        $task = Task::find($id);

        if (!$task) {
            return $this->notFoundResponse($id);
        }

        $task->delete();
        $this->clearTaskCache();

        return response()->json(['message' => 'Task deleted successfully']);
    }

    protected function generateCacheKey(Request $request)
    {
        $key = 'tasks';

        if ($request->has('status')) {
            $key .= '_status_' . $request->status;
        }

        if ($request->has('priority')) {
            $key .= '_priority_' . $request->priority;
        }

        if ($request->has('user_id')) {
            $key .= '_user_id_' . $request->user_id;
        }

        return $key;
    }

    protected function clearTaskCache()
    {
        cache()->forget('tasks');
    }

    private function validateTask(Request $request, $isUpdate = false)
    {
        $rules = [
            'title' => $isUpdate ? 'sometimes|required|string|max:255' : 'required|string|max:255',
            'description' => $isUpdate ? 'sometimes|required|string' : 'required|string',
            'status' => $isUpdate ? 'sometimes|required|in:pending,completed' : 'required|in:pending,completed',
            'priority' => $isUpdate ? 'sometimes|required|in:low,medium,high' : 'required|in:low,medium,high',
            'order' => $isUpdate ? 'sometimes|required|integer' : '',
        ];

        return $request->validate($rules);
    }

    private function notFoundResponse($id)
    {
        return response()->json([
            'error' => 'Task not found',
            'message' => "Task with ID {$id} was not found.",
        ], 404);
    }
}