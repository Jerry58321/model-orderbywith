<?php

namespace Jerry58321\ModelOrderByWith;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Jerry58321\ModelOrderByWith\Supports\QueryAs;

/**
 * @method Builder orderByWith($withRelation, $column, $direction = 'asc');
 */
class BuilderServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function boot()
    {
        $this->macroOrderByWith();
    }

    /**
     * @return void
     */
    public function macroOrderByWith()
    {
        Builder::macro('orderByWith', function ($withRelation, $column, $direction = 'asc') {
            $getQuery = function ($query, $relation, $parentQuery, $column, $builder = null) {
                $relation = $query->getRelationWithoutConstraints($relation);
                $builder = $relation->getRelationExistenceQuery($builder ?? $relation->getQuery(), $parentQuery, $column);
                return [$relation, $builder];
            };

            $with = function (array $withRelation, $column) use ($getQuery) {
                $tables = [];
                $query = $this;
                foreach ($withRelation as $relation) {
                    /** @var Relation $relation */
                    [$relation, $builder] = $getQuery($query, $relation, $this, $column, $builder ?? null);
                    $tables[] = $relation->getModel()->getTable();
                    $query = $relation->getQuery();
                }
                return [$builder ?? null, $tables ?? []];
            };

            if (is_string($withRelation)) {
                [$builder, $tables] = $with(explode('.', $withRelation), $column);
            }

            if (is_array($withRelation)) {
                [$withRelation, $closure] = [array_key_first($withRelation), collect($withRelation)->first()];
                /** @var Builder $builder */
                [$builder, $tables] = $with(explode('.', $withRelation), $column);
                /** @var QueryAs $queryAs */
                $queryAs = $closure(new QueryAs($builder, Arr::last($tables)));
                $builder = $queryAs->getQuery();
            }

            return $this->orderBy(
                $builder->from(DB::raw(implode(',', $tables))),
                $direction
            );
        });
    }
}