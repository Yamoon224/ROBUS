<?php

namespace App\Models\Concerns;

use App\Support\SiblingModels;

/**
 * Supprime les requêtes N+1 pendant le rendu des vues.
 *
 * Quand une relation non chargée est lue sur un modèle issu d'une collection
 * (paginate(), get(), relation hasMany...), elle est chargée en une seule
 * requête pour tous les modèles de cette collection au lieu d'une requête
 * par ligne. Hors rendu de vue (API, jobs, logique métier), le comportement
 * d'Eloquent reste inchangé.
 */
trait BatchesLazyLoads
{
    public function newCollection(array $models = [])
    {
        if (count($models) > 1) {
            SiblingModels::register($models);
        }

        return parent::newCollection($models);
    }

    public function getRelationValue($key)
    {
        if (! $this->relationLoaded($key) && SiblingModels::shouldBatch() && $this->isRelation($key)) {
            SiblingModels::load($this, $key);
        }

        return parent::getRelationValue($key);
    }
}
