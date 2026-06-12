<?php

use App\Http\Controllers\Api\V1\GeoJsonController;
use App\Http\Controllers\Api\V1\RoutingController;
use App\Http\Controllers\Api\V1\SpatialAnalysisController;
use App\Models\EvacuationPoint;
use App\Models\FloodEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

$floodEventMissing = fn () => response()->json([
    'success' => false,
    'message' => 'Kejadian banjir tidak ditemukan.',
    'errors' => [],
], 404);

$routingMissing = function (Request $request) {
    $segments = $request->segments();
    $floodEventId = $segments[4] ?? null;
    $evacuationPointId = $segments[6] ?? null;

    if ($floodEventId && ! FloodEvent::query()->whereKey($floodEventId)->exists()) {
        return response()->json([
            'success' => false,
            'message' => 'Kejadian banjir tidak ditemukan.',
            'errors' => [],
        ], 404);
    }

    if ($evacuationPointId && ! EvacuationPoint::query()->whereKey($evacuationPointId)->exists()) {
        return response()->json([
            'success' => false,
            'message' => 'Titik evakuasi tidak ditemukan.',
            'errors' => [],
        ], 404);
    }

    return response()->json([
        'success' => false,
        'message' => 'Data routing tidak ditemukan.',
        'errors' => [],
    ], 404);
};

Route::prefix('v1')->name('api.v1.')->group(function () use ($floodEventMissing, $routingMissing): void {
    Route::prefix('geojson')->name('geojson.')->group(function (): void {
        Route::get('/flood-risks', [GeoJsonController::class, 'floodRisks'])->name('flood-risks');
        Route::get('/flood-events', [GeoJsonController::class, 'floodEvents'])->name('flood-events');
        Route::get('/evacuation-points', [GeoJsonController::class, 'evacuationPoints'])->name('evacuation-points');
        Route::get('/heavy-equipment-posts', [GeoJsonController::class, 'heavyEquipmentPosts'])->name('heavy-equipment-posts');
        Route::get('/district-flood-intensity', [GeoJsonController::class, 'districtFloodIntensity'])->name('district-flood-intensity');
    });

    Route::prefix('analysis')->name('analysis.')->group(function () use ($floodEventMissing): void {
        Route::get('/flood-events/{floodEvent}/nearest-evacuation', [SpatialAnalysisController::class, 'nearestEvacuation'])
            ->name('flood-events.nearest-evacuation')
            ->missing($floodEventMissing);
        Route::get('/flood-events/{floodEvent}/nearest-equipment', [SpatialAnalysisController::class, 'nearestEquipment'])
            ->name('flood-events.nearest-equipment')
            ->missing($floodEventMissing);
        Route::get('/flood-events/{floodEvent}/nearest-resources', [SpatialAnalysisController::class, 'nearestResources'])
            ->name('flood-events.nearest-resources')
            ->missing($floodEventMissing);
    });

    Route::prefix('routing')->name('routing.')->group(function () use ($floodEventMissing, $routingMissing): void {
        Route::get('/flood-events/{floodEvent}/to-nearest-evacuation', [RoutingController::class, 'routeToNearestEvacuation'])
            ->name('flood-events.to-nearest-evacuation')
            ->missing($floodEventMissing);
        Route::get('/flood-events/{floodEvent}/to-evacuation/{evacuationPoint}', [RoutingController::class, 'routeToEvacuation'])
            ->name('flood-events.to-evacuation')
            ->missing($routingMissing);
    });
});
