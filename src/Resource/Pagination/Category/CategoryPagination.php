<?php

namespace App\Resource\Pagination\Category;

use App\Resource\Filtering\Category\CategoryEntityFilter;
use App\Resource\Filtering\EntityFilterInterface;
use App\Resource\Pagination\AbstractPagination;
use App\Resource\Pagination\PaginationInterface;

class CategoryPagination
    extends AbstractPagination
    implements PaginationInterface
{
    private const ROUTE = 'get_categories';

    /**
     * @var CategoryEntityFilter
     */
    private $entityFilter;

    /**
     * MoviePagination constructor.
     * @param CategoryEntityFilter $resourceFilter
     */
    public function __construct(CategoryEntityFilter $resourceFilter)
    {
        $this->entityFilter = $resourceFilter;
    }

    /**
     * @return EntityFilterInterface
     */
    public function getEntityFilter(): EntityFilterInterface
    {
        return $this->entityFilter;
    }

    /**
     * @return string
     */
    public function getRouteName(): string
    {
        return self::ROUTE;
    }
}