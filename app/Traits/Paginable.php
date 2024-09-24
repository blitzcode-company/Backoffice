<?php

namespace App\Traits;

use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

trait Paginable
{
    public function paginateBuilder($builder, $perPage = 9, $page = null)
    {
        return $builder->paginate($perPage, '*', 'page', $page);
    }

    public function paginateCollection(Collection $collection, $perPage = 9, $page = null)
    {
        $page = $page ?: request()->input('page', 1);
        $offset = ($page * $perPage) - $perPage;
        return new LengthAwarePaginator(
            $collection->slice($offset, $perPage),
            $collection->count(),
            $perPage,
            $page,
            [
                'path' => request()->url(),
                'query' => request()->query(),
            ]
        );
    }
}
