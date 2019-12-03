<?php

namespace App\Resource\Pagination;

use App\Resource\Filtering\FilterInterface;
use App\Resource\Filtering\EntityFilterInterface;
use Hateoas\Representation\PaginatedRepresentation;

interface PaginationInterface
{
    /**
     * @param Page $page
     * @param FilterInterface $filter
     * @return PaginatedRepresentation
     */
    public function paginate(Page $page, FilterInterface $filter): PaginatedRepresentation;

    /**
     * @return EntityFilterInterface
     */
    public function getEntityFilter(): EntityFilterInterface;

    /**
     * @return string
     */
    public function getRouteName(): string;
}