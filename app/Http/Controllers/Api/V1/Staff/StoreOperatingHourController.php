<?php

namespace App\Http\Controllers\Api\V1\Staff;

use App\Enums\DayOfWeek;
use App\Http\Controllers\Controller;
use App\Http\Resources\StoreOperatingHourResource;
use App\Http\Resources\StoreResource;
use App\Models\Store;
use App\Models\StoreOperatingHour;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StoreOperatingHourController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Store $store)
    {
        // $operatingHours = $store
        //     ->operatingHours()
        //     ->orderBy('day_of_week')
        //     ->get();

        $operatingHours = $store->operatingHours
            ->keyBy('day_of_week');

        $data = collect(DayOfWeek::cases())->map(function ($day) use ($operatingHours) {
            $operatingHour = $operatingHours->get($day->value);

            return [
                'id' => $operatingHour?->id,
                'store_id' => $operatingHour?->store_id,
                'day_of_week' => $day->value,
                'day_name' => $day->label(),
                'open_time' => $operatingHour?->open_time,
                'close_time' => $operatingHour?->close_time,
                'is_open' => $operatingHour
                    ? $operatingHour->is_open
                    : false,

            ];
        });

        return response()->json([
            'success' => true,
            'message' => 'List Data jam operasional toko',
            'data' => $data,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(StoreOperatingHour $storeOperatingHour)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Store $store)
    {
        $validated = $request->validate([
            'operating_hours' => [
                'required',
                'array',
            ],

            'operating_hours.*.day_of_week' => [
                'required',
                'integer',
                'between:0,6',
            ],

            'operating_hours.*.is_open' => [
                'required',
                'boolean',
            ],

            'operating_hours.*.open_time' => [
                'nullable',
                'date_format:H:i:s',
            ],

            'operating_hours.*.close_time' => [
                'nullable',
                'date_format:H:i:s',
            ],
        ]);

        DB::transaction(function () use ($validated, $store) {
            foreach ($validated['operating_hours'] as $item) {
                StoreOperatingHour::updateOrCreate(
                    [
                        'store_id' => $store->id,
                        'day_of_week' => $item['day_of_week'],
                    ],
                    [
                        'is_open' => $item['is_open'],

                        'open_time' => $item['is_open']
                            ? $item['open_time']
                            : null,

                        'close_time' => $item['is_open']
                            ? $item['close_time']
                            : null,
                    ],
                );
            }
        });

        return response()->json([
            'success' => true,
            'message' => 'Jam operasional toko berhasil diperbarui.',
            'data' => new StoreResource($store->fresh()),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StoreOperatingHour $storeOperatingHour)
    {
        //
    }
}
