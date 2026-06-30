<?php

namespace App\Http\Controllers\Api;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Resources\StoreResource;
use App\Models\Store;
use App\Services\FileUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class StoreController extends Controller
{
    public function __construct(
        private FileUploadService $fileUploadService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        Gate::authorize('viewAny', Store::class);

        $user = $request->user();

        if ($user->hasRole(UserRole::SuperAdmin)) {
            $stores = Store::query()->paginate(10);
        } else {
            $stores = $user->stores()->paginate(10);
        }


        return response()->json([
            'success' => true,
            'message' => 'List Data Store',
            'data' => StoreResource::collection($stores),
            'meta' => [
                'current_page' => $stores->currentPage(),
                'last_page' => $stores->lastPage(),
                'per_page' => $stores->perPage(),
                'total' => $stores->total(),
            ],
            'links' => [
                'next' => $stores->nextPageUrl(),
                'prev' => $stores->previousPageUrl(),
            ],
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Gate::authorize('create', Store::class);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:1024',
            'banner' => 'nullable|image|mimes:jpeg,png,jpg|max:1024',
            'address' => 'required|string',
            'phone' => 'required|string|max:15',
            'latitude' => 'nullable|string',
            'longitude' => 'nullable|string',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $this->fileUploadService->upload(
                $request->file('image'),
                'images/stores/profile'
            );
        }

        if ($request->hasFile('banner')) {
            $validated['banner'] = $this->fileUploadService->upload(
                $request->file('banner'),
                'images/stores/banner'
            );
        }

        $store = Store::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Store created successfully',
            'data' => new StoreResource($store),
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Store $store)
    {
        Gate::authorize('view', $store);

        // $store->load([
        //     'todayOperatingHour',
        //     'menuCategories' => function ($query) {
        //         $query->where('is_active', true)
        //             ->with(['menus']);
        //     },
        // ]);

        return response()->json([
            'success' => true,
            'message' => 'Detail Store',
            'data' => new StoreResource($store),
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Store $store)
    {
        Gate::authorize('update', $store);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:1024',
            'banner' => 'nullable|image|mimes:jpeg,png,jpg|max:1024',
            'address' => 'required|string',
            'phone' => 'required|string|max:15',
            'latitude' => 'nullable|string',
            'longitude' => 'nullable|string',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $this->fileUploadService->replace(
                $store->image,
                $request->file('image'),
                'images/stores/profile'
            );
        }

        if ($request->hasFile('banner')) {
            $validated['banner'] = $this->fileUploadService->replace(
                $store->banner,
                $request->file('banner'),
                'images/stores/banner'
            );
        }

        $store->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Store updated successfully',
            'data' => new StoreResource($store->fresh()),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Store $store)
    {
        Gate::authorize('delete', $store);

        if ($store->image) {
            $this->fileUploadService->delete($store->image);
        }

        if ($store->banner) {
            $this->fileUploadService->delete($store->banner);
        }

        $store->delete();

        return response()->json([
            'success' => true,
            'message' => 'Store deleted successfully',
        ], 200);
    }
}
