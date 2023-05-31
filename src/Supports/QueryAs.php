<?php

namespace Jerry58321\ModelOrderByWith\Supports;

use Illuminate\Database\Eloquent\Builder;

class QueryAs
{
    /**
     * @var Builder
     */
    protected $query;
    /**
     * @var string
     */
    private $prefix;
    /**
     * @var array
     */
    private $callMethods;

    /**
     * @param  Builder  $query
     * @param  string  $prefix
     */
    public function __construct(Builder $query, string $prefix)
    {
        $this->query = $query;
        $this->prefix = $prefix;
        $this->callMethods = [];
    }

    /**
     * @return Builder
     */
    public function getQuery(): Builder
    {
        foreach ($this->callMethods as $method => $args) {
            $this->query->{$method}(...$args);
        }
        return $this->query;
    }

    /**
     * @param  array  $columns
     * @return $this
     */
    public function prefixColumns(array $columns): QueryAs
    {
        $this->callMethods = collect($this->callMethods)->map(function ($args, $methods) use ($columns) {
            return collect($args)->map(function ($arg) use ($columns) {
                return in_array($arg, $columns) ? "$this->prefix.$arg" : $arg;
            })->toArray();
        })->toArray();

        return $this;
    }

    public function __call($name, $arguments)
    {
        $this->callMethods[$name] = $arguments;

        return $this;
    }
}