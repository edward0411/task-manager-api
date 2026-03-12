<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ExternalPostService;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ExternalPostController extends Controller
{
    public function __construct(
        private readonly ExternalPostService $externalPostService
    ) {
    }

    public function index(Request $request): JsonResponse
    {
        try {
            $posts = $this->externalPostService->getPosts(
                $request->only(['userId'])
            );

            return response()->json([
                'message' => 'External posts fetched successfully.',
                'data' => $posts,
            ], 200);
        } catch (RequestException $exception) {
            return response()->json([
                'message' => 'Failed to fetch external posts.',
                'error' => $exception->getMessage(),
            ], 500);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $post = $this->externalPostService->getPostById($id);

            if (empty($post)) {
                return response()->json([
                    'message' => 'External post not found.',
                ], 404);
            }

            return response()->json([
                'message' => 'External post fetched successfully.',
                'data' => $post,
            ], 200);
        } catch (RequestException $exception) {
            return response()->json([
                'message' => 'Failed to fetch external post.',
                'error' => $exception->getMessage(),
            ], 500);
        }
    }
}