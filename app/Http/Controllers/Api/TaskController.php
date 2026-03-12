<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Task::query()->where('user_id', $request->user()->id);

        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->string('priority'));
        }

        if ($request->filled('search')) {
            $search = $request->string('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $tasks = $query->latest()->paginate($request->integer('per_page', 10));

        return response()->json($tasks);
    }

    public function store(StoreTaskRequest $request): JsonResponse
    {
        $task = Task::create([
            'user_id' => $request->user()->id,
            'title' => $request->string('title'),
            'description' => $request->input('description'),
            'status' => $request->input('status', 'pending'),
            'priority' => $request->input('priority', 'medium'),
            'due_date' => $request->input('due_date'),
        ]);

        return response()->json([
            'message' => 'Task created successfully.',
            'data' => $task,
        ], 201);
    }

    public function show(Request $request, Task $task): JsonResponse
    {
        if ($task->user_id !== $request->user()->id) {
            return response()->json([
                'message' => 'Task not found.',
            ], 404);
        }

        return response()->json([
            'data' => $task,
        ]);
    }

    public function update(UpdateTaskRequest $request, Task $task): JsonResponse
    {
        if ($task->user_id !== $request->user()->id) {
            return response()->json([
                'message' => 'Task not found.',
            ], 404);
        }

        $task->update($request->validated());

        return response()->json([
            'message' => 'Task updated successfully.',
            'data' => $task->fresh(),
        ]);
    }

    public function destroy(Request $request, Task $task): JsonResponse
    {
        if ($task->user_id !== $request->user()->id) {
            return response()->json([
                'message' => 'Task not found.',
            ], 404);
        }

        $task->delete();

        return response()->json([
            'message' => 'Task deleted successfully.',
        ]);
    }
}