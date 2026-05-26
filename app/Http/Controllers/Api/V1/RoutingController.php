<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\EvacuationPoint;
use App\Models\FloodEvent;
use App\Services\RoutingService;
use App\Services\SpatialAnalysisService;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use InvalidArgumentException;
use RuntimeException;

class RoutingController extends Controller
{
    public function __construct(
        private readonly RoutingService $routing,
        private readonly SpatialAnalysisService $analysis,
    ) {}

    public function routeToNearestEvacuation(Request $request, FloodEvent $floodEvent): JsonResponse
    {
        $filters = $this->validatedFilters($request, [
            'type' => ['nullable', Rule::in(EvacuationPoint::TYPES)],
            'max_distance_meters' => ['nullable', 'integer', 'min:1'],
        ]);

        $recommendations = $this->analysis->nearestEvacuations($floodEvent, [
            'limit' => 1,
            'type' => $filters['type'] ?? null,
            'max_distance_meters' => $filters['max_distance_meters'] ?? null,
        ]);

        if ($recommendations->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada titik evakuasi aktif yang cocok untuk routing.',
                'data' => null,
            ]);
        }

        $evacuationPoint = EvacuationPoint::query()->findOrFail($recommendations->first()->id);

        return $this->routeResponse($floodEvent, $evacuationPoint);
    }

    public function routeToEvacuation(Request $request, FloodEvent $floodEvent, EvacuationPoint $evacuationPoint): JsonResponse
    {
        if ($evacuationPoint->status !== 'aktif') {
            return response()->json([
                'success' => false,
                'message' => 'Titik evakuasi tidak aktif dan tidak dapat digunakan untuk routing.',
                'errors' => [
                    'evacuation_point' => ['Titik evakuasi harus berstatus aktif.'],
                ],
            ], 422);
        }

        return $this->routeResponse($floodEvent, $evacuationPoint);
    }

    /**
     * @param  array<string, mixed>  $rules
     * @return array<string, mixed>
     */
    private function validatedFilters(Request $request, array $rules): array
    {
        $validator = Validator::make($request->query(), $rules);

        if ($validator->fails()) {
            throw new HttpResponseException(response()->json([
                'success' => false,
                'message' => 'Validasi parameter gagal.',
                'errors' => $validator->errors(),
            ], 422));
        }

        return collect($validator->validated())
            ->reject(fn ($value): bool => $value === null || $value === '')
            ->all();
    }

    private function routeResponse(FloodEvent $floodEvent, EvacuationPoint $evacuationPoint): JsonResponse
    {
        try {
            return response()->json([
                'success' => true,
                'message' => 'Rute evakuasi referensi berhasil diambil.',
                'data' => $this->routing->routeFromFloodEventToEvacuation($floodEvent, $evacuationPoint),
            ]);
        } catch (InvalidArgumentException $exception) {
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage(),
                'errors' => [],
            ], 422);
        } catch (RuntimeException $exception) {
            $status = in_array($exception->getCode(), [422, 502], true)
                ? $exception->getCode()
                : 502;

            return response()->json([
                'success' => false,
                'message' => $exception->getMessage(),
                'errors' => [],
            ], $status);
        }
    }
}
