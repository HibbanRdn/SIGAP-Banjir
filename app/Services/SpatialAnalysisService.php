<?php

namespace App\Services;

use App\Models\EvacuationPoint;
use App\Models\FloodEvent;
use App\Models\HeavyEquipmentPost;
use App\Models\HeavyEquipmentUnit;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use InvalidArgumentException;

class SpatialAnalysisService
{
    /**
     * @param  array<string, mixed>  $filters
     * @return Collection<int, EvacuationPoint>
     */
    public function nearestEvacuations(FloodEvent $floodEvent, array $filters = []): Collection
    {
        $limit = (int) ($filters['limit'] ?? 3);

        $query = EvacuationPoint::query()
            ->select('evacuation_points.*')
            ->crossJoin('flood_events as source_event')
            ->where('source_event.id', $floodEvent->id)
            ->where('evacuation_points.status', 'aktif')
            ->when($filters['type'] ?? null, fn (Builder $query, string $type) => $query->where('evacuation_points.type', $type))
            ->selectRaw('ST_X(evacuation_points.geom) AS longitude')
            ->selectRaw('ST_Y(evacuation_points.geom) AS latitude')
            ->selectRaw(
                'ROUND(ST_Distance(source_event.geom::geography, evacuation_points.geom::geography)::numeric, 2) AS distance_meters'
            )
            ->when($filters['max_distance_meters'] ?? null, function (Builder $query, int|string $meters): void {
                $query->whereRaw(
                    'ST_DWithin(source_event.geom::geography, evacuation_points.geom::geography, ?)',
                    [(int) $meters],
                );
            })
            ->orderByRaw('ST_Distance(source_event.geom::geography, evacuation_points.geom::geography) ASC')
            ->limit($limit);

        return $query->get();
    }

    /**
     * @param  array<string, mixed>  $filters
     * @return Collection<int, HeavyEquipmentPost>
     */
    public function nearestEquipmentPosts(FloodEvent $floodEvent, array $filters = []): Collection
    {
        $limit = (int) ($filters['limit'] ?? 3);
        $equipmentType = $filters['equipment_type'] ?? null;

        $availableUnitFilter = function ($query) use ($equipmentType): void {
            $query->available()
                ->when($equipmentType, function (Builder $query, string $type): void {
                    $query->whereHas('type', fn (Builder $query) => $query->where('name', $type));
                });
        };

        $query = HeavyEquipmentPost::query()
            ->select('heavy_equipment_posts.*')
            ->crossJoin('flood_events as source_event')
            ->where('source_event.id', $floodEvent->id)
            ->where('heavy_equipment_posts.status', 'aktif')
            ->whereHas('units', $availableUnitFilter)
            ->with([
                'units' => function ($query) use ($availableUnitFilter): void {
                    $availableUnitFilter($query);
                    $query->with('type')->orderBy('equipment_type_id');
                },
            ])
            ->selectRaw('ST_X(heavy_equipment_posts.geom) AS longitude')
            ->selectRaw('ST_Y(heavy_equipment_posts.geom) AS latitude')
            ->selectRaw(
                'ROUND(ST_Distance(source_event.geom::geography, heavy_equipment_posts.geom::geography)::numeric, 2) AS distance_meters'
            )
            ->when($filters['max_distance_meters'] ?? null, function (Builder $query, int|string $meters): void {
                $query->whereRaw(
                    'ST_DWithin(source_event.geom::geography, heavy_equipment_posts.geom::geography, ?)',
                    [(int) $meters],
                );
            })
            ->orderByRaw('ST_Distance(source_event.geom::geography, heavy_equipment_posts.geom::geography) ASC')
            ->limit($limit);

        return $query->get();
    }

    /**
     * @param  array<string, mixed>  $filters
     * @return array<string, mixed>
     */
    public function nearestResources(FloodEvent $floodEvent, array $filters = []): array
    {
        return [
            'flood_event' => $this->floodEventSummary($floodEvent),
            'nearest_evacuations' => $this->formatEvacuationRecommendations($this->nearestEvacuations($floodEvent, [
                'limit' => $filters['evacuation_limit'] ?? 3,
                'max_distance_meters' => $filters['max_distance_meters'] ?? null,
            ])),
            'nearest_equipment_posts' => $this->formatEquipmentPostRecommendations($this->nearestEquipmentPosts($floodEvent, [
                'limit' => $filters['equipment_limit'] ?? 3,
                'max_distance_meters' => $filters['max_distance_meters'] ?? null,
            ])),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function floodEventSummary(FloodEvent $floodEvent): array
    {
        $event = FloodEvent::query()
            ->select('flood_events.*')
            ->selectRaw('CASE WHEN flood_events.geom IS NULL OR ST_IsEmpty(flood_events.geom) THEN NULL ELSE ST_X(flood_events.geom) END AS longitude')
            ->selectRaw('CASE WHEN flood_events.geom IS NULL OR ST_IsEmpty(flood_events.geom) THEN NULL ELSE ST_Y(flood_events.geom) END AS latitude')
            ->findOrFail($floodEvent->id);

        if ($event->longitude === null || $event->latitude === null) {
            throw new InvalidArgumentException('Geometri kejadian banjir belum tersedia.');
        }

        return [
            'id' => $event->id,
            'name' => $event->name,
            'status' => $event->status,
            'severity_level' => $event->severity_level,
            'district' => $event->district,
            'longitude' => round((float) $event->longitude, 7),
            'latitude' => round((float) $event->latitude, 7),
        ];
    }

    /**
     * @param  Collection<int, EvacuationPoint>  $points
     * @return array<int, array<string, mixed>>
     */
    public function formatEvacuationRecommendations(Collection $points): array
    {
        return $points
            ->values()
            ->map(fn (EvacuationPoint $point, int $index): array => [
                'rank' => $index + 1,
                'id' => $point->id,
                'name' => $point->name,
                'type' => $point->type,
                'status' => $point->status,
                'capacity' => $point->capacity,
                'district' => $point->district,
                'longitude' => round((float) $point->longitude, 7),
                'latitude' => round((float) $point->latitude, 7),
                'distance_meters' => round((float) $point->distance_meters, 2),
                'distance_label' => $this->formatDistanceLabel((float) $point->distance_meters),
            ])
            ->all();
    }

    /**
     * @param  Collection<int, HeavyEquipmentPost>  $posts
     * @return array<int, array<string, mixed>>
     */
    public function formatEquipmentPostRecommendations(Collection $posts): array
    {
        return $posts
            ->values()
            ->map(fn (HeavyEquipmentPost $post, int $index): array => [
                'rank' => $index + 1,
                'id' => $post->id,
                'name' => $post->name,
                'status' => $post->status,
                'district' => $post->district,
                'longitude' => round((float) $post->longitude, 7),
                'latitude' => round((float) $post->latitude, 7),
                'distance_meters' => round((float) $post->distance_meters, 2),
                'distance_label' => $this->formatDistanceLabel((float) $post->distance_meters),
                'available_equipment' => $post->units
                    ->map(fn (HeavyEquipmentUnit $unit): array => [
                        'type' => $unit->type?->name,
                        'quantity' => $unit->quantity,
                        'available_quantity' => $unit->available_quantity,
                        'status' => $unit->status,
                    ])
                    ->values()
                    ->all(),
            ])
            ->all();
    }

    public function formatDistanceLabel(float $meters): string
    {
        if ($meters < 1000) {
            return number_format($meters, 0, '.', '').' m';
        }

        $kilometers = number_format($meters / 1000, 2, '.', '');
        $kilometers = rtrim(rtrim($kilometers, '0'), '.');

        return $kilometers.' km';
    }
}
