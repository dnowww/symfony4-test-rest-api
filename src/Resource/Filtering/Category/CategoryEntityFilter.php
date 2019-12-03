<?php

namespace App\Resource\Filtering\Category;

use App\Repository\CategoryRepository;
use App\Resource\Filtering\EntityFilterInterface;
use Doctrine\ORM\QueryBuilder;

class CategoryEntityFilter implements EntityFilterInterface
{
    /**
     * @var CategoryRepository
     */
    private $categoryRepository;

    /**
     * CategoryResourceFilter constructor.
     * @param CategoryRepository $categoryRepository
     */
    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @param CategoryFilter $filter
     * @return QueryBuilder
     */
    public function getResources($filter): QueryBuilder
    {
        $qb = $this->getQuery($filter);
        $qb->select('category');

        return $qb;
    }

    /**
     * @param CategoryFilter $filter
     * @return QueryBuilder
     */
    public function getResourceCount($filter): QueryBuilder
    {
        $qb = $this->getQuery($filter, 'count');
        $qb->select('count(category)');

        return $qb;
    }

    /**
     * @param CategoryFilter $filter
     * @param null|string $count
     * @return QueryBuilder
     */
    public function getQuery(CategoryFilter $filter, ?string $count = null): QueryBuilder
    {
        $qb = $this->categoryRepository->createQueryBuilder('category');

        $qb->where('1=1');

        if (null !== $filter->getArticles()) {
            $qb->join('category.articles', 'category_articles');
            $qb->andwhere(
                $qb->expr()->eq('category_articles', ':category_articles')
            );
            $qb->setParameter('category_articles', $filter->getArticles());
        }

        if (null !== $filter->getTitle()) {
            $qb->andwhere(
                $qb->expr()->like('LOWER(category.name)', ':name')
            );
            $qb->setParameter('name', "%" . mb_strtolower($filter->getTitle()) . "%");
        }


        if (null !== $filter->getSortByArray() && $count === null) {
            foreach ($filter->getSortByArray() as $by => $order) {
                $expr = 'desc' == $order
                    ? $qb->expr()->desc("category.$by")
                    : $qb->expr()->asc("category.$by");
                $qb->addOrderBy($expr);
            }
        }

        return $qb;
    }

}