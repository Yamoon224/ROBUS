<?php

namespace App\Http\Resources\Concerns;

use App\Support\BatchingResourceCollection;
use App\Support\SiblingModels;

/**
 * Supprime les requêtes N+1 pendant la sérialisation JSON d'une ressource :
 * une relation lue sur un élément de la liste est chargée en une seule
 * requête pour tous les éléments (voir App\Models\Concerns\BatchesLazyLoads).
 */
trait BatchesLazyLoads
{
    public function resolve($request = null)
    {
        return SiblingModels::whileBatching(function () use ($request) {
            return parent::resolve($request);
        });
    }

    public static function collection($resource)
    {
        return tap(new BatchingResourceCollection($resource, static::class), function ($collection) {
            if (property_exists(static::class, 'preserveKeys')) {
                $collection->preserveKeys = (new static([]))->preserveKeys === true;
            }
        });
    }
}
