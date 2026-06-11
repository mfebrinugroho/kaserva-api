<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\StoreResource;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class StoreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $stores = Store::with('todayOperatingHour')->get();

        return response()->json([
            'success' => true,
            'message' => 'List Data Store',
            'data' => StoreResource::collection($stores),
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'latitude' => 'nullable|string',
            'longitude' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $image = $request->file('image');

        if ($image) {
            $imageName = Str::uuid() . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('stores', $imageName, 'public');
            $validated['image'] = $path;
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
        $validated = $request->validate([
            'name' => 'string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'is_open' => 'boolean',
        ]);

        // $image = $request->file('image');

        // if ($image) {
        //     # code...
        // }

        // $store->update($validated);

        // return response()->json([
        //     'success' => true,
        //     'message' => 'Store updated successfully',
        //     'data' => new StoreResource($store),
        // ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Store $store)
    {
        if ($store->image) {
            Storage::disk('public')->delete($store->image);
        }

        $store->delete();

        return response()->json([
            'success' => true,
            'message' => 'Store deleted successfully',
        ], 200);
    }
}
