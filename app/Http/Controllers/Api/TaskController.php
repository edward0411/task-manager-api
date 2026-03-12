<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Services\TaskService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function __construct(
        private readonly TaskService $taskService
    ) {
    }

    public function index(Request $request): JsonResponse
    {
        $tasks = $this->taskService->listUserTasks(
            $request->user(),
            $request->only(['status', 'priority', 'search', 'per_page'])
        );

        return response()->json([
            'message' => 'Tasks fetched successfully.',
            'data' => TaskResource::collection($tasks->items()),
            'meta' => [
                'current_page' => $tasks->currentPage(),
                'last_page' => $tasks->lastPage(),
                'per_page' => $tasks->perPage(),
                'total' => $tasks->total(),
            ],
        ]);
    }

    public function store(StoreTaskRequest $request): JsonResponse
    {
        $task = $this->taskService->createTask(
            $request->user(),
            $request->validated()
        );

        return response()->json([
            'message' => 'Task created successfully.',
            'data' => new TaskResource($task),
        ], 201);
    }

    public function show(Request $request, Task $task): JsonResponse
    {
        if (! $this->taskService->belongsToUser($task, $request->user())) {
            return response()->json([
                'message' => 'Task not found.',
            ], 404);
        }

        return response()->json([
            'message' => 'Task fetched successfully.',
            'data' => new TaskResource($task),
        ]);
    }

    public function update(UpdateTaskRequest $request, Task $task): JsonResponse
    {
        if (! $this->taskService->belongsToUser($task, $request->user())) {
            return response()->json([
                'message' => 'Task not found.',
            ], 404);
        }

        $task = $this->taskService->updateTask($task, $request->validated());

        return response()->json([
            'message' => 'Task updated successfully.',
            'data' => new TaskResource($task),
        ]);
    }

    public function destroy(Request $request, Task $task): JsonResponse
    {
        if (! $this->taskService->belongsToUser($task, $request->user())) {
            return response()->json([
                'message' => 'Task not found.',
            ], 404);
        }

        $this->taskService->deleteTask($task);

        return response()->json([
            'message' => 'Task deleted successfully.',
        ]);
    }
}