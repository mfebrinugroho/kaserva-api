<?php

namespace App\Http\Controllers\Api\Staff;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\Staff\Store\StoreOptionResource;
use App\Http\Resources\Api\Staff\Store\StoreResource;
use App\Models\Store;
use App\Models\User;
use App\Services\FileUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

        $perPage = $request->get('limit', 10);

        $search = $request->get('search');

        if ($user->hasRole(UserRole::SuperAdmin)) {
            $stores = Store::with('owner')->search($search)
                ->latest()
                ->orderBy('id', 'asc')
                ->paginate($perPage)
                ->withQueryString();
        } else {
            $stores = $user->stores()
                ->search($search)
                ->orderBy('id', 'asc')
                ->paginate($perPage)
                ->withQueryString();
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
            'name' => 'required|string|max:100',
            'description' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'is_active' => 'required|boolean',
        ]);

        $store = Store::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Resto/Toko berhasil dibuat.',
            'data' => new StoreResource($store),
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Store $store)
    {
        Gate::authorize('view', $store);

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
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:15',
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
            'message' => 'Resto/Toko berhasil diperbarui.',
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
            'message' => 'Resto/Toko berhasil dihapus.',
        ], 200);
    }

    public function options()
    {
        $stores = Store::select('id', 'name')->where('is_active', true)->get();

        return response()->json([
            'success' => true,
            'message' => 'List Data Resto',
            'data' => StoreOptionResource::collection($stores),
        ], 200);
    }

    public function updateStatus(Request $request, Store $store)
    {
        // Gate::authorize('updateStatus', $store);

        $validated = $request->validate([
            'is_active' => ['required', 'boolean'],
        ]);

        $store->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Status toko berhasil diperbarui',
            'data' => new StoreResource($store->fresh()),
        ]);
    }

    public function updateStatusOperational(Request $request, Store $store)
    {
        // Gate::authorize('updateStatusOperational', $store);

        $validated = $request->validate([
            'is_open' => ['required', 'boolean'],
        ]);

        $store->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Status operasional toko berhasil diperbarui',
            'data' => new StoreResource($store->fresh()),
        ]);
    }

    public function updateStatusOrder(Request $request, Store $store)
    {
        // Gate::authorize('updateStatusOrder', $store);

        $validated = $request->validate([
            'is_accept_order' => ['required', 'boolean'],
        ]);

        $store->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Status order toko berhasil diperbarui',
            'data' => new StoreResource($store->fresh()),
        ]);
    }

    public function storesWithoutOwner()
    {
        $stores = Store::doesntHave('owner')
            ->orderBy('name')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Data list resto/toko yang tersedia',
            'data' => StoreResource::collection($stores),
        ]);
    }



    public function addOwner(Request $request)
    {
        // Gate::authorize('addOwner', Store::class);

        $validated = $request->validate([
            'store_id' => ['required', 'exists:stores,id'],
            'user_id' => ['required', 'exists:users,id'],
        ]);

        $store = Store::findOrFail($validated['store_id']);
        $owner = User::with(['role', 'stores'])->findOrFail($validated['user_id']);

        // Pastikan user adalah owner
        if ($owner->role->slug !== UserRole::Owner) {
            return response()->json([
                'success' => false,
                'message' => 'User bukan owner.'
            ], 422);
        }

        if ($store->owner()->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Store sudah memiliki owner.'
            ], 422);
        }

        // Tambahkan jika belum terhubung
        DB::transaction(function () use ($store, $owner) {
            $store->users()->attach($owner->id);
        });

        return response()->json([
            'success' => true,
            'message' => 'Owner berhasil ditambahkan.',
            'data' => new StoreResource($store),
        ]);
    }
}
