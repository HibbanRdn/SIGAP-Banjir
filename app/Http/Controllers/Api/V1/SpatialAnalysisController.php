<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\EvacuationPoint;
use App\Models\FloodEvent;
use App\Services\SpatialAnalysisService;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use InvalidArgumentException;

class SpatialAnalysisController extends Controller
{
    public function __construct(private readonly SpatialAnalysisService $analysis) {}

    public function nearestEvacuation(Request $request, FloodEvent $floodEvent): JsonResponse
    {
        $filters = $this->validatedFilters($request, [
            'limit' => ['nullable', 'integer', 'min:1', 'max:10'],
            'max_distance_meters' => ['nullable', 'integer', 'min:1'],
            'type' => ['nullable', Rule::in(EvacuationPoint::TYPES)],
        ]);

        $filters['limit'] ??= 3;

        try {
            $floodEventSummary = $this->analysis->floodEventSummary($floodEvent);
            $recommendations = $this->analysis->nearestEvacuations($floodEvent, $filters);

            return response()->json([
                'success' => true,
                'message' => $recommendations->isEmpty()
                    ? 'Tidak ada titik evakuasi aktif yang sesuai dengan filter.'
                    : 'Rekomendasi titik evakuasi terdekat berhasil diambil.',
                'data' => [
                    'flood_event' => $floodEventSummary,
                    'recommendations' => $this->analysis->formatEvacuationRecommendations($recommendations),
                ],
            ]);
        } catch (InvalidArgumentException $exception) {
            return $this->analysisError($exception->getMessage());
        }
    }

    public function nearestEquipment(Request $request, FloodEvent $floodEvent): JsonResponse
    {
        $filters = $this->validatedFilters($request, [
            'limit' => ['nullable', 'integer', 'min:1', 'max:10'],
            'max_distance_meters' => ['nullable', 'integer', 'min:1'],
            'equipment_type' => ['nullable', 'string', Rule::exists('equipment_types', 'name')],
        ]);

        $filters['limit'] ??= 3;

        try {
            $floodEventSummary = $this->analysis->floodEventSummary($floodEvent);
            $recommendations = $this->analysis->nearestEquipmentPosts($floodEvent, $filters);

            return response()->json([
                'success' => true,
                'message' => $recommendations->isEmpty()
                    ? 'Tidak ada pos alat berat aktif dan tersedia yang sesuai dengan filter.'
                    : 'Rekomendasi pos alat berat terdekat berhasil diambil.',
                'data' => [
                    'flood_event' => $floodEventSummary,
                    'recommendations' => $this->analysis->formatEquipmentPostRecommendations($recommendations),
                ],
            ]);
        } catch (InvalidArgumentException $exception) {
            return $this->analysisError($exception->getMessage());
        }
    }

    public function nearestResources(Request $request, FloodEvent $floodEvent): JsonResponse
    {
        $filters = $this->validatedFilters($request, [
            'evacuation_limit' => ['nullable', 'integer', 'min:1', 'max:10'],
            'equipment_limit' => ['nullable', 'integer', 'min:1', 'max:10'],
            'max_distance_meters' => ['nullable', 'integer', 'min:1'],
        ]);

        $filters['evacuation_limit'] ??= 3;
        $filters['equipment_limit'] ??= 3;

        try {
            return response()->json([
                'success' => true,
                'message' => 'Rekomendasi resource terdekat berhasil diambil.',
                'data' => $this->analysis->nearestResources($floodEvent, $filters),
            ]);
        } catch (InvalidArgumentException $exception) {
            return $this->analysisError($exception->getMessage());
        }
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

    private function analysisError(string $message): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => [],
        ], 422);
    }
}
