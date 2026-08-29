<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\MenuResource;
use App\Models\Menu;
use App\Services\FileUploadService;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function __construct(
        private FileUploadService $fileUploadService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->get('limit', 10);

        $search = $request->get('search');

        $menus = Menu::with(['category', 'store'])
            ->search($search)
            ->paginate($perPage)
            ->withQueryString();

        return response()->json([
            'success' => true,
            'message' => 'List Data User',
            'data' => MenuResource::collection($menus),
            'meta' => [
                'current_page' => $menus->currentPage(),
                'last_page' => $menus->lastPage(),
                'per_page' => $menus->perPage(),
                'total' => $menus->total(),
            ],
            'links' => [
                'next' => $menus->nextPageUrl(),
                'prev' => $menus->previousPageUrl(),
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
            'menu_category_id' => 'required|numeric',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:1024',
            'sort_order' => 'required|numeric',
            'is_available' => 'required|boolean',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $this->fileUploadService->upload(
                $request->file('image'),
                'images/menus'
            );
        }

        $menu = Menu::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Menu berhasil dibuat.',
            'data' => new MenuResource($menu),
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Menu $menu)
    {
        $menu->load('store', 'category');

        return response()->json([
            'success' => true,
            'message' => 'Detail Menu',
            'data' => new MenuResource($menu),
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Menu $menu)
    {
        $validated = $request->validate([
            'store_id' => 'required|numeric',
            'menu_category_id' => 'required|numeric',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:1024',
            'sort_order' => 'required|numeric',
            'is_available' => 'required|boolean',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $this->fileUploadService->replace(
                $menu->image,
                $request->file('image'),
                'images/menus'
            );
        }

        $menu->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Menu berhasil diperbarui.',
            'data' => new MenuResource($menu->fresh()),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Menu $menu)
    {
        if ($menu->image) {
            $this->fileUploadService->delete($menu->image);
        }

        $menu->delete();

        return response()->json([
            'success' => true,
            'message' => 'Menu berhasil dihapus.',
        ], 200);
    }

    public function updateStatus(Request $request, Menu $menu)
    {

        $validated = $request->validate([
            'is_available' => ['required', 'boolean'],
        ]);

        $menu->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Status menu berhasil diperbarui',
            'data' => new MenuResource($menu->fresh()),
        ]);
    }
}
