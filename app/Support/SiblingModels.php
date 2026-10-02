<?php

namespace App\Support;

use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Model;
use Throwable;
use WeakMap;
use WeakReference;

/**
 * Garde, pour chaque modèle hydraté dans une collection, la liste des modèles
 * chargés par la même requête. Utilisé par le trait BatchesLazyLoads.
 */
final class SiblingModels
{
    /** @var WeakMap<Model, object>|null */
    private static ?WeakMap $groups = null;

    private static ?bool $enabled = null;

    private static int $forced = 0;

    public static function register(array $models): void
    {
        self::$groups ??= new WeakMap();

        $group = new \stdClass();
        $group->models = [];

        foreach ($models as $model) {
            if ($model instanceof Model) {
                $group->models[] = WeakReference::create($model);
                self::$groups[$model] = $group;
            }
        }
    }

    /**
     * Le regroupement n'est actif que pendant le rendu d'une vue ou la
     * sérialisation d'une ressource JSON (lecture seule) : la logique métier
     * garde des lectures fraîches, relation par relation.
     */
    public static function shouldBatch(): bool
    {
        if (self::$groups === null) {
            return false;
        }

        self::$enabled ??= (bool) config('database.batch_lazy_loads', true);

        return self::$enabled && (self::$forced > 0 || ! app('view')->doneRendering());
    }

    /**
     * Active le regroupement le temps d'un traitement en lecture seule.
     */
    public static function whileBatching(callable $callback)
    {
        self::$forced++;

        try {
            return $callback();
        } finally {
            self::$forced--;
        }
    }

    public static function load(Model $model, string $relation): void
    {
        $group = self::$groups[$model] ?? null;

        if ($group === null) {
            return;
        }

        $siblings = [];

        foreach ($group->models as $reference) {
            $sibling = $reference->get();

            if ($sibling !== null && ! $sibling->relationLoaded($relation)) {
                $siblings[] = $sibling;
            }
        }

        if (count($siblings) < 2) {
            return;
        }

        try {
            (new EloquentCollection($siblings))->load($relation);
        } catch (Throwable $e) {
            // En cas d'échec, on retire toute relation initialisée à vide pour
            // qu'Eloquent la recharge normalement (lazy loading).
            foreach ($siblings as $sibling) {
                $sibling->unsetRelation($relation);
            }
        }
    }
}
