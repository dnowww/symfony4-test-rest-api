<?php

namespace App\Resource\Pagination\Article;

use App\Resource\Filtering\Article\ArticleEntityFilter;
use App\Resource\Filtering\EntityFilterInterface;
use App\Resource\Pagination\AbstractPagination;
use App\Resource\Pagination\PaginationInterface;

class ArticlePagination
    extends AbstractPagination
    implements PaginationInterface
{
    private const ROUTE = 'get_articles';

    /**
     * @var ArticleEntityFilter
     */
    private $entityFilter;

    /**
     * MoviePagination constructor.
     * @param ArticleEntityFilter $resourceFilter
     */
    public function __construct(ArticleEntityFilter $resourceFilter)
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