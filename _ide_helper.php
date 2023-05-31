<?php

namespace Illuminate\Database\Eloquent {

    abstract class Model {

        /**
         * @param $withRelation
         * @param $column
         * @param  string  $direction
         * @return Builder
         * @see \Jerry58321\ModelOrderByWith\BuilderServiceProvider::orderByWith()
         */
        public static function orderByWith($withRelation, $column, string $direction = 'asc'): Builder
        {
            return Builder::orderByWith($withRelation, $column, $direction);
        }
    }
}

namespace Illuminate\Database\Eloquent {

    use Jerry58321\ModelOrderByWith\Supports\QueryAs;

    class Builder {
        /**
         * @param $withRelation
         * @param $column
         * @param  string  $direction
         * @return Builder
         * @see \Jerry58321\ModelOrderByWith\BuilderServiceProvider::orderByWith()
         */
        public function orderByWith($withRelation, $column, string $direction = 'asc'): Builder
        {
            return Builder::orderByWith($withRelation, $column, $direction);
        }

        /**
         * @param  array  $columns
         * @return QueryAs
         */
        public function prefixColumns(array $columns): QueryAs
        {
            return QueryAs::prefixColumns($columns);
        }
    }
}