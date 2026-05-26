<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use JsonException;

class GeoJsonService
{
    /**
     * @return array<string, mixed>
     *
     * @throws JsonException
     */
    public function pointFeatureCollection(Builder $query, callable $properties): array
    {
        $model = $query->getModel();
        $table = $model->getTable();

        if ($query->getQuery()->columns === null) {
            $query->select("{$table}.*");
        }

        $items = $query
            ->selectRaw("ST_AsGeoJSON({$table}.geom) AS geometry_json")
            ->get();

        return $this->featureCollection($items, $properties);
    }

    /**
     * @param  Collection<int, mixed>  $items
     * @return array<string, mixed>
     *
     * @throws JsonException
     */
    private function featureCollection(Collection $items, callable $properties): array
    {
        return [
            'type' => 'FeatureCollection',
            'features' => $items
                ->map(fn ($item): array => [
                    'type' => 'Feature',
                    'geometry' => json_decode($item->geometry_json, true, 512, JSON_THROW_ON_ERROR),
                    'properties' => $properties($item),
                ])
                ->values()
                ->all(),
        ];
    }
}
