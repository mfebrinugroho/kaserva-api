<?php

namespace App\Http\Controllers\Api\V1\Staff;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Resources\MenuCategoryOptionResource;
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
                ->orderBy('is_active', 'DESC')
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

        $menu_category = MenuCategory::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Kategori Menu berhasil dibuat.',
            'data' => new MenuCategoryResource($menu_category),
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
    public function update(Request $request, MenuCategory $menu_category)
    {
        $validated = $request->validate([
            'store_id' => 'required|numeric|exists:stores,id',
            'name' => 'required|string|max:255',
            'sort_order' => 'required|numeric',
            'is_active' => 'required|boolean',
        ]);

        $menu_category->update($validated);

        $menu_category->load('store');

        return response()->json([
            'success' => true,
            'message' => 'Kategori menu berhasil diperbarui.',
            'data' => new MenuCategoryResource($menu_category->fresh()),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MenuCategory $menu_category)
    {
        $menu_category->delete();

        return response()->json([
            'success' => true,
            'message' => 'Kategori menu berhasil dihapus',
        ], 200);
    }

    public function options(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'store_id' => ['required', 'exists:stores,id'],
        ]);

        $storeId = $request->store_id;

        // if ($user->role->name === 'super-admin') {
        //     $request->validate([
        //         'store_id' => ['required', 'exists:stores,id'],
        //     ]);

        //     $storeId = $request->store_id;
        // } else {
        //     $storeId = $user->store_id;

        //     if (!$storeId) {
        //         return response()->json([
        //             'success' => false,
        //             'message' => 'Toko aktif belum dipilih.',
        //         ], 422);
        //     }
        // }

        $menu_categories = MenuCategory::select('id', 'store_id', 'name')->where('store_id', $storeId)->orderBy('name')->get();

        return response()->json([
            'success' => true,
            'message' => 'List Data Kategori Menu',
            'data' => MenuCategoryOptionResource::collection($menu_categories),
        ], 200);
    }

    public function updateStatus(Request $request, MenuCategory $menu_category)
    {

        $validated = $request->validate([
            'is_active' => ['required', 'boolean'],
        ]);

        $menu_category->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Status menu berhasil diperbarui',
            'data' => new MenuCategoryResource($menu_category->fresh()),
        ]);
    }
}
