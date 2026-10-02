<?php

namespace App\Support;

use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * Collection de ressources renvoyée par Resource::collection() : mêmes données
 * que celle de Laravel, avec le regroupement des relations pendant la sérialisation.
 */
class BatchingResourceCollection extends AnonymousResourceCollection
{
    public function resolve($request = null)
    {
        return SiblingModels::whileBatching(function () use ($request) {
            return parent::resolve($request);
        });
    }
}
