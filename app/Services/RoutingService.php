<?php

namespace App\Services;

use App\Models\EvacuationPoint;
use App\Models\FloodEvent;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use InvalidArgumentException;
use RuntimeException;

class RoutingService
{
    private const REFERENCE_NOTE = 'Rute ini bersifat referensi dan belum mempertimbangkan jalan tertutup akibat banjir.';

    /**
     * @return array<string, mixed>
     */
    public function routeBetweenPoints(
        float $originLongitude,
        float $originLatitude,
        float $destinationLongitude,
        float $destinationLatitude,
    ): array {
        if (config('services.routing.provider', 'osrm') !== 'osrm') {
            throw new RuntimeException('Provider routing tidak didukung.', 502);
        }

        $baseUrl = rtrim((string) config('services.routing.osrm_base_url', 'https://router.project-osrm.org'), '/');
        $coordinates = "{$originLongitude},{$originLatitude};{$destinationLongitude},{$destinationLatitude}";

        try {
            $response = Http::timeout(10)
                ->acceptJson()
                ->get("{$baseUrl}/route/v1/driving/{$coordinates}", [
                    'overview' => 'full',
                    'geometries' => 'geojson',
                    'steps' => 'false',
                ]);
        } catch (ConnectionException) {
            throw new RuntimeException('Provider routing tidak merespons.', 502);
        }

        if (! $response->successful()) {
            throw new RuntimeException('Provider routing tidak merespons.', 502);
        }

        $payload = $response->json();

        if (! is_array($payload)) {
            throw new RuntimeException('Response provider routing tidak valid.', 502);
        }

        if (($payload['code'] ?? null) === 'NoRoute') {
            throw new RuntimeException('Rute tidak ditemukan oleh provider routing.', 422);
        }

        if (($payload['code'] ?? null) !== 'Ok' || empty($payload['routes'][0])) {
            throw new RuntimeException('Response provider routing tidak valid.', 502);
        }

        return $payload;
    }

    /**
     * @return array<string, mixed>
     */
    public function routeFromFloodEventToEvacuation(FloodEvent $floodEvent, EvacuationPoint $evacuationPoint): array
    {
        $origin = $this->floodEventCoordinates($floodEvent);
        $destination = $this->evacuationPointCoordinates($evacuationPoint);

        $response = $this->routeBetweenPoints(
            $origin['longitude'],
            $origin['latitude'],
            $destination['longitude'],
            $destination['latitude'],
        );

        return $this->formatOsrmResponse($response, $floodEvent, $evacuationPoint);
    }

    /**
     * @param  array<string, mixed>  $response
     * @return array<string, mixed>
     */
    public function formatOsrmResponse(array $response, FloodEvent $floodEvent, EvacuationPoint $evacuationPoint): array
    {
        $origin = $this->floodEventCoordinates($floodEvent);
        $destination = $this->evacuationPointCoordinates($evacuationPoint);
        $route = $response['routes'][0] ?? null;

        if (! is_array($route) || ! isset($route['geometry']['type'], $route['geometry']['coordinates'])) {
            throw new RuntimeException('Response provider routing tidak valid.', 502);
        }

        if ($route['geometry']['type'] !== 'LineString' || ! is_array($route['geometry']['coordinates']) || $route['geometry']['coordinates'] === []) {
            throw new RuntimeException('Response provider routing tidak valid.', 502);
        }

        if (! isset($route['distance'], $route['duration']) || ! is_numeric($route['distance']) || ! is_numeric($route['duration'])) {
            throw new RuntimeException('Response provider routing tidak valid.', 502);
        }

        $distanceMeters = round((float) ($route['distance'] ?? 0), 2);
        $durationSeconds = round((float) ($route['duration'] ?? 0), 2);

        return [
            'provider' => 'osrm',
            'route_status' => 'referensi',
            'note' => self::REFERENCE_NOTE,
            'origin' => [
                'type' => 'flood_event',
                'id' => $origin['id'],
                'name' => $origin['name'],
                'longitude' => $origin['longitude'],
                'latitude' => $origin['latitude'],
            ],
            'destination' => [
                'type' => 'evacuation_point',
                'id' => $destination['id'],
                'name' => $destination['name'],
                'longitude' => $destination['longitude'],
                'latitude' => $destination['latitude'],
            ],
            'distance_meters' => $distanceMeters,
            'distance_label' => $this->distanceLabel($distanceMeters),
            'duration_seconds' => $durationSeconds,
            'duration_label' => $this->durationLabel($durationSeconds),
            'geometry' => [
                'type' => 'LineString',
                'coordinates' => $route['geometry']['coordinates'],
            ],
        ];
    }

    public function distanceLabel(float $meters): string
    {
        if ($meters < 1000) {
            return number_format($meters, 0, '.', '').' m';
        }

        $kilometers = number_format($meters / 1000, 2, '.', '');
        $kilometers = rtrim(rtrim($kilometers, '0'), '.');

        return $kilometers.' km';
    }

    public function durationLabel(float $seconds): string
    {
        if ($seconds < 60) {
            return number_format($seconds, 0, '.', '').' detik';
        }

        $minutes = max(1, (int) round($seconds / 60));

        if ($minutes < 60) {
            return $minutes.' menit';
        }

        $hours = intdiv($minutes, 60);
        $remainingMinutes = $minutes % 60;

        if ($remainingMinutes === 0) {
            return $hours.' jam';
        }

        return "{$hours} jam {$remainingMinutes} menit";
    }

    /**
     * @return array{id: int, name: string, longitude: float, latitude: float}
     */
    private function floodEventCoordinates(FloodEvent $floodEvent): array
    {
        $event = FloodEvent::query()
            ->select('flood_events.id', 'flood_events.name')
            ->selectRaw('CASE WHEN flood_events.geom IS NULL OR ST_IsEmpty(flood_events.geom) THEN NULL ELSE ST_X(flood_events.geom) END AS longitude')
            ->selectRaw('CASE WHEN flood_events.geom IS NULL OR ST_IsEmpty(flood_events.geom) THEN NULL ELSE ST_Y(flood_events.geom) END AS latitude')
            ->findOrFail($floodEvent->id);

        if ($event->longitude === null || $event->latitude === null) {
            throw new InvalidArgumentException('Geometri kejadian banjir belum tersedia.');
        }

        return [
            'id' => (int) $event->id,
            'name' => (string) $event->name,
            'longitude' => round((float) $event->longitude, 7),
            'latitude' => round((float) $event->latitude, 7),
        ];
    }

    /**
     * @return array{id: int, name: string, longitude: float, latitude: float}
     */
    private function evacuationPointCoordinates(EvacuationPoint $evacuationPoint): array
    {
        $point = EvacuationPoint::query()
            ->select('evacuation_points.id', 'evacuation_points.name')
            ->selectRaw('CASE WHEN evacuation_points.geom IS NULL OR ST_IsEmpty(evacuation_points.geom) THEN NULL ELSE ST_X(evacuation_points.geom) END AS longitude')
            ->selectRaw('CASE WHEN evacuation_points.geom IS NULL OR ST_IsEmpty(evacuation_points.geom) THEN NULL ELSE ST_Y(evacuation_points.geom) END AS latitude')
            ->findOrFail($evacuationPoint->id);

        if ($point->longitude === null || $point->latitude === null) {
            throw new InvalidArgumentException('Geometri titik evakuasi belum tersedia.');
        }

        return [
            'id' => (int) $point->id,
            'name' => (string) $point->name,
            'longitude' => round((float) $point->longitude, 7),
            'latitude' => round((float) $point->latitude, 7),
        ];
    }
}
