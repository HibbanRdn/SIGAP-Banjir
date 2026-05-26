<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\EvacuationPoint;
use App\Models\FloodEvent;
use App\Models\FloodRiskPoint;
use App\Models\HeavyEquipmentPost;
use App\Models\HeavyEquipmentUnit;
use App\Services\GeoJsonService;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use JsonException;

class GeoJsonController extends Controller
{
    public function __construct(private readonly GeoJsonService $geoJson) {}

    /**
     * @throws JsonException
     */
    public function floodRisks(Request $request): JsonResponse
    {
        $filters = $this->validatedFilters($request, [
            'risk_level' => ['nullable', Rule::in(FloodRiskPoint::RISK_LEVELS)],
            'district' => ['nullable', 'string', 'max:120'],
            'subdistrict' => ['nullable', 'string', 'max:120'],
            'data_status' => ['nullable', Rule::in(FloodRiskPoint::DATA_STATUSES)],
            'source_type' => ['nullable', Rule::in(FloodRiskPoint::SOURCE_TYPES)],
            'limit' => ['nullable', 'integer', 'min:1', 'max:500'],
        ]);

        $query = FloodRiskPoint::query()
            ->when($filters['risk_level'] ?? null, fn (Builder $query, string $riskLevel) => $query->where('risk_level', $riskLevel))
            ->when($filters['district'] ?? null, fn (Builder $query, string $district) => $query->where('district', $district))
            ->when($filters['subdistrict'] ?? null, fn (Builder $query, string $subdistrict) => $query->where('subdistrict', $subdistrict))
            ->when($filters['data_status'] ?? null, fn (Builder $query, string $dataStatus) => $query->where('data_status', $dataStatus))
            ->when($filters['source_type'] ?? null, fn (Builder $query, string $sourceType) => $query->where('source_type', $sourceType))
            ->orderBy('risk_level')
            ->orderBy('name');

        $this->applyLimit($query, $filters);

        return response()->json($this->geoJson->pointFeatureCollection(
            $query,
            fn (FloodRiskPoint $point): array => [
                'id' => $point->id,
                'name' => $point->name,
                'address' => $point->address,
                'district' => $point->district,
                'subdistrict' => $point->subdistrict,
                'risk_level' => $point->risk_level,
                'description' => $point->description,
                'source_type' => $point->source_type,
                'source_reference' => $point->source_reference,
                'is_verified' => (bool) $point->is_verified,
                'data_status' => $point->data_status,
                'created_at' => $this->date($point->created_at),
                'updated_at' => $this->date($point->updated_at),
            ],
        ));
    }

    /**
     * @throws JsonException
     */
    public function floodEvents(Request $request): JsonResponse
    {
        $filters = $this->validatedFilters($request, [
            'status' => ['nullable', Rule::in(FloodEvent::STATUSES)],
            'severity_level' => ['nullable', Rule::in(FloodEvent::SEVERITY_LEVELS)],
            'district' => ['nullable', 'string', 'max:120'],
            'subdistrict' => ['nullable', 'string', 'max:120'],
            'data_status' => ['nullable', Rule::in(FloodEvent::DATA_STATUSES)],
            'source_type' => ['nullable', Rule::in(FloodEvent::SOURCE_TYPES)],
            'limit' => ['nullable', 'integer', 'min:1', 'max:500'],
        ]);

        $query = FloodEvent::query()
            ->when($filters['status'] ?? null, fn (Builder $query, string $status) => $query->where('status', $status))
            ->when($filters['severity_level'] ?? null, fn (Builder $query, string $severityLevel) => $query->where('severity_level', $severityLevel))
            ->when($filters['district'] ?? null, fn (Builder $query, string $district) => $query->where('district', $district))
            ->when($filters['subdistrict'] ?? null, fn (Builder $query, string $subdistrict) => $query->where('subdistrict', $subdistrict))
            ->when($filters['data_status'] ?? null, fn (Builder $query, string $dataStatus) => $query->where('data_status', $dataStatus))
            ->when($filters['source_type'] ?? null, fn (Builder $query, string $sourceType) => $query->where('source_type', $sourceType))
            ->orderByDesc('reported_at')
            ->orderBy('name');

        $this->applyLimit($query, $filters);

        return response()->json($this->geoJson->pointFeatureCollection(
            $query,
            fn (FloodEvent $event): array => [
                'id' => $event->id,
                'name' => $event->name,
                'address' => $event->address,
                'district' => $event->district,
                'subdistrict' => $event->subdistrict,
                'severity_level' => $event->severity_level,
                'water_depth_cm' => $event->water_depth_cm,
                'status' => $event->status,
                'description' => $event->description,
                'source_type' => $event->source_type,
                'source_reference' => $event->source_reference,
                'occurred_at' => $this->date($event->occurred_at),
                'reported_at' => $this->date($event->reported_at),
                'is_verified' => (bool) $event->is_verified,
                'data_status' => $event->data_status,
                'created_at' => $this->date($event->created_at),
                'updated_at' => $this->date($event->updated_at),
            ],
        ));
    }

    /**
     * @throws JsonException
     */
    public function evacuationPoints(Request $request): JsonResponse
    {
        $filters = $this->validatedFilters($request, [
            'status' => ['nullable', Rule::in(EvacuationPoint::STATUSES)],
            'type' => ['nullable', Rule::in(EvacuationPoint::TYPES)],
            'district' => ['nullable', 'string', 'max:120'],
            'subdistrict' => ['nullable', 'string', 'max:120'],
            'data_status' => ['nullable', Rule::in(EvacuationPoint::DATA_STATUSES)],
            'source_type' => ['nullable', Rule::in(EvacuationPoint::SOURCE_TYPES)],
            'limit' => ['nullable', 'integer', 'min:1', 'max:500'],
        ]);

        $query = EvacuationPoint::query()
            ->when($filters['status'] ?? null, fn (Builder $query, string $status) => $query->where('status', $status))
            ->when($filters['type'] ?? null, fn (Builder $query, string $type) => $query->where('type', $type))
            ->when($filters['district'] ?? null, fn (Builder $query, string $district) => $query->where('district', $district))
            ->when($filters['subdistrict'] ?? null, fn (Builder $query, string $subdistrict) => $query->where('subdistrict', $subdistrict))
            ->when($filters['data_status'] ?? null, fn (Builder $query, string $dataStatus) => $query->where('data_status', $dataStatus))
            ->when($filters['source_type'] ?? null, fn (Builder $query, string $sourceType) => $query->where('source_type', $sourceType))
            ->orderBy('status')
            ->orderBy('name');

        $this->applyLimit($query, $filters);

        return response()->json($this->geoJson->pointFeatureCollection(
            $query,
            fn (EvacuationPoint $point): array => [
                'id' => $point->id,
                'name' => $point->name,
                'type' => $point->type,
                'address' => $point->address,
                'district' => $point->district,
                'subdistrict' => $point->subdistrict,
                'capacity' => $point->capacity,
                'facilities' => $this->csvList($point->facilities),
                'status' => $point->status,
                'description' => $point->description,
                'source_type' => $point->source_type,
                'source_reference' => $point->source_reference,
                'is_verified' => (bool) $point->is_verified,
                'data_status' => $point->data_status,
                'created_at' => $this->date($point->created_at),
                'updated_at' => $this->date($point->updated_at),
            ],
        ));
    }

    /**
     * @throws JsonException
     */
    public function heavyEquipmentPosts(Request $request): JsonResponse
    {
        $filters = $this->validatedFilters($request, [
            'status' => ['nullable', Rule::in(HeavyEquipmentPost::STATUSES)],
            'district' => ['nullable', 'string', 'max:120'],
            'subdistrict' => ['nullable', 'string', 'max:120'],
            'equipment_type' => ['nullable', 'string', Rule::exists('equipment_types', 'name')],
            'data_status' => ['nullable', Rule::in(HeavyEquipmentPost::DATA_STATUSES)],
            'source_type' => ['nullable', Rule::in(HeavyEquipmentPost::SOURCE_TYPES)],
            'limit' => ['nullable', 'integer', 'min:1', 'max:500'],
        ]);

        $query = HeavyEquipmentPost::query()
            ->with(['units.type'])
            ->when($filters['status'] ?? null, fn (Builder $query, string $status) => $query->where('status', $status))
            ->when($filters['district'] ?? null, fn (Builder $query, string $district) => $query->where('district', $district))
            ->when($filters['subdistrict'] ?? null, fn (Builder $query, string $subdistrict) => $query->where('subdistrict', $subdistrict))
            ->when($filters['equipment_type'] ?? null, function (Builder $query, string $equipmentType): void {
                $query->whereHas('units.type', fn (Builder $query) => $query->where('name', $equipmentType));
            })
            ->when($filters['data_status'] ?? null, fn (Builder $query, string $dataStatus) => $query->where('data_status', $dataStatus))
            ->when($filters['source_type'] ?? null, fn (Builder $query, string $sourceType) => $query->where('source_type', $sourceType))
            ->orderBy('status')
            ->orderBy('name');

        $this->applyLimit($query, $filters);

        return response()->json($this->geoJson->pointFeatureCollection(
            $query,
            fn (HeavyEquipmentPost $post): array => [
                'id' => $post->id,
                'name' => $post->name,
                'address' => $post->address,
                'district' => $post->district,
                'subdistrict' => $post->subdistrict,
                'status' => $post->status,
                'description' => $post->description,
                'source_type' => $post->source_type,
                'source_reference' => $post->source_reference,
                'is_verified' => (bool) $post->is_verified,
                'data_status' => $post->data_status,
                'total_units' => (int) $post->units->sum('quantity'),
                'available_units' => (int) $post->units->sum('available_quantity'),
                'equipment_summary' => $post->units
                    ->map(fn (HeavyEquipmentUnit $unit): array => [
                        'type' => $unit->type?->name,
                        'quantity' => $unit->quantity,
                        'available_quantity' => $unit->available_quantity,
                        'status' => $unit->status,
                    ])
                    ->values()
                    ->all(),
                'created_at' => $this->date($post->created_at),
                'updated_at' => $this->date($post->updated_at),
            ],
        ));
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
                'message' => 'Query parameter tidak valid.',
                'errors' => $validator->errors(),
            ], 400));
        }

        return collect($validator->validated())
            ->reject(fn ($value): bool => $value === null || $value === '')
            ->all();
    }

    /**
     * @param  array<string, mixed>  $filters
     */
    private function applyLimit(Builder $query, array $filters): void
    {
        if (isset($filters['limit'])) {
            $query->limit((int) $filters['limit']);
        }
    }

    private function date(mixed $value): ?string
    {
        if ($value instanceof DateTimeInterface) {
            return $value->format(DateTimeInterface::ATOM);
        }

        return $value ? (string) $value : null;
    }

    /**
     * @return array<int, string>
     */
    private function csvList(?string $value): array
    {
        if (! $value) {
            return [];
        }

        return collect(explode(',', $value))
            ->map(fn (string $item): string => trim($item))
            ->filter()
            ->values()
            ->all();
    }
}
