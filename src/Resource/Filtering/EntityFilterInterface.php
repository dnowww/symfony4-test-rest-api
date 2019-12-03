<?php

namespace App\Resource\Filtering;

use Doctrine\ORM\QueryBuilder;

interface EntityFilterInterface
{
    /**
     * @param $filter
     * @return QueryBuilder
     */
    public function getResourceCount($filter): QueryBuilder;

    /**
     * @param $filter
     * @return QueryBuilder
     */
    public function getResources($filter): QueryBuilder;
}