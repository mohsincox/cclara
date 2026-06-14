<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Chapter\StoreChapterRequest;
use App\Http\Requests\Chapter\UpdateChapterRequest;
use App\Http\Resources\Chapter\ChapterResource;
use App\Models\Chapter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ChapterController extends Controller
{
    /**
     * Display a listing of the chapters.
     *
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Chapter::with('book');

        // Filter by category
        if ($request->has('book_id')) {
            $query->byBook($request->book_id);
        }

        // Search by name
        if ($request->has('search')) {
            $query->search($request->search);
        }

        // Sort by field
        $sortBy = $request->get('sort_by', 'id');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        // Pagination
        $perPage = min((int) $request->get('per_page', 15), 100);
        $chapters = $query->paginate($perPage);

        return ChapterResource::collection($chapters);
    }

    /**
     * Store a newly created chapter.
     *
     * @param StoreChapterRequest $request
     * @return ChapterResource
     */
    public function store(StoreChapterRequest $request): ChapterResource
    {
        $chapter = Chapter::create($request->validated());

        return new ChapterResource($chapter->load('book'));
    }

    /**
     * Display the specified chapter.
     *
     * @param Chapter $chapter
     * @return ChapterResource
     */
    public function show(Chapter $chapter): ChapterResource
    {
        return new ChapterResource($chapter->load('book'));
    }

    /**
     * Update the specified chapter.
     *
     * @param UpdateChapterRequest $request
     * @param Chapter $chapter
     * @return ChapterResource
     */
    public function update(UpdateChapterRequest $request, Chapter $chapter): ChapterResource
    {
        $chapter->update($request->validated());

        return new ChapterResource($chapter->load('book'));
    }

    /**
     * Remove the specified chapter.
     *
     * @param Chapter $chapter
     * @return JsonResponse
     */
    public function destroy(Chapter $chapter): JsonResponse
    {
        $chapter->delete();

        return response()->json([
            'message' => 'Chapter deleted successfully.',
        ], 200);
    }
}
