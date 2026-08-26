<?php

namespace App\Http\Controllers\Api;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Resources\MenuCategoryResource;
use App\Models\MenuCategory;
use Illuminate\Http\Request;

class MenuCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $perPage = $request->get('limit', 10);

        $search = $request->get('search');

        if ($user->hasRole(UserRole::SuperAdmin)) {
            $menu_categories = MenuCategory::with('store')
                ->search($search)
                ->paginate($perPage)
                ->withQueryString();
        } else {
            $menu_categories = MenuCategory::where('store_id', $user->store_id)
                ->search($search)
                ->paginate($perPage)
                ->withQueryString();
        }

        return response()->json([
            'success' => true,
            'message' => 'List Data User',
            'data' => MenuCategoryResource::collection($menu_categories),
            'meta' => [
                'current_page' => $menu_categories->currentPage(),
                'last_page' => $menu_categories->lastPage(),
                'per_page' => $menu_categories->perPage(),
                'total' => $menu_categories->total(),
            ],
            'links' => [
                'next' => $menu_categories->nextPageUrl(),
                'prev' => $menu_categories->previousPageUrl(),
            ],
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'store_id' => 'required|numeric',
            'name' => 'required|string|max:255',
            'sort_order' => 'required|numeric',
            'is_active' => 'required|boolean',
        ]);

        $category = MenuCategory::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Kategori Menu berhasil dibuat.',
            'data' => new MenuCategoryResource($category),
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
