<?php

namespace App\Resource\Pagination;

use App\Resource\Filtering\FilterInterface;
use Doctrine\ORM\UnexpectedResultException;
use Hateoas\Representation\CollectionRepresentation;
use Hateoas\Representation\PaginatedRepresentation;

abstract class AbstractPagination implements PaginationInterface
{
    /**
     * @param Page $page
     * @param FilterInterface $filter
     * @return PaginatedRepresentation
     */
    public function paginate(Page $page, FilterInterface $filter): PaginatedRepresentation
    {
        $resources = $this->getEntityFilter()->getResources($filter)
            ->setFirstResult($page->getOffset())
            ->setMaxResults($page->getLimit())
            ->getQuery()
            ->getResult();

        $resourceCount = $pages = null;

        try {
            $resourceCount = $this->getEntityFilter()->getResourceCount($filter)
                ->getQuery()
                ->getSingleScalarResult();
            $pages = ceil($resourceCount / $page->getLimit());
        } catch (UnexpectedResultException $e) {

        }

        return new PaginatedRepresentation(
            new CollectionRepresentation($resources),
            $this->getRouteName(),
            $filter->getQueryParameters(),
            $page->getPage(),
            $page->getLimit(),
            $pages,
            null,
            null,
            false,
            $resourceCount
        );
    }
}