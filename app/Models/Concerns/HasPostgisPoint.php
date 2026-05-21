<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;

trait HasPostgisPoint
{
    /**
     * Tambahkan alias koordinat dari kolom geom.
     *
     * Kolom geom tetap menjadi sumber utama data spasial. Longitude dan latitude
     * hanya alias hasil query untuk kebutuhan tampilan/form. Urutan PostGIS:
     * ST_MakePoint(longitude, latitude), SRID 4326.
     */
    public function scopeWithCoordinates(Builder $query): Builder
    {
        $this->selectBaseColumnsIfMissing($query);

        return $query->selectRaw(
            "ST_X({$this->qualifiedGeomColumn()}) AS longitude, ST_Y({$this->qualifiedGeomColumn()}) AS latitude"
        );
    }

    public function scopeWithDistanceFrom(Builder $query, float $longitude, float $latitude): Builder
    {
        $this->selectBaseColumnsIfMissing($query);

        return $query->selectRaw(
            "ST_Distance({$this->qualifiedGeomColumn()}::geography, ST_SetSRID(ST_MakePoint(?, ?), 4326)::geography) AS distance_meters",
            [$longitude, $latitude],
        );
    }

    public function scopeWithinDistanceOf(Builder $query, float $longitude, float $latitude, float $meters): Builder
    {
        return $query->whereRaw(
            "ST_DWithin({$this->qualifiedGeomColumn()}::geography, ST_SetSRID(ST_MakePoint(?, ?), 4326)::geography, ?)",
            [$longitude, $latitude, $meters],
        );
    }

    public function scopeOrderByDistanceFrom(Builder $query, float $longitude, float $latitude): Builder
    {
        return $query->orderByRaw(
            "ST_Distance({$this->qualifiedGeomColumn()}::geography, ST_SetSRID(ST_MakePoint(?, ?), 4326)::geography) ASC",
            [$longitude, $latitude],
        );
    }

    public function getLongitudeAttribute(mixed $value): ?float
    {
        return $value === null ? null : (float) $value;
    }

    public function getLatitudeAttribute(mixed $value): ?float
    {
        return $value === null ? null : (float) $value;
    }

    public function getDistanceMetersAttribute(mixed $value): ?float
    {
        return $value === null ? null : (float) $value;
    }

    private function qualifiedGeomColumn(): string
    {
        return $this->qualifyColumn('geom');
    }

    private function selectBaseColumnsIfMissing(Builder $query): void
    {
        if ($query->getQuery()->columns === null) {
            $query->select($this->getTable().'.*');
        }
    }
}
