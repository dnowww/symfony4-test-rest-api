<?php

namespace App\Resource\Filtering\Article;

use App\Repository\ArticleRepository;
use App\Resource\Filtering\EntityFilterInterface;
use Doctrine\ORM\QueryBuilder;

class ArticleEntityFilter implements EntityFilterInterface
{
    /**
     * @var ArticleRepository
     */
    private $articleRepository;

    /**
     * ArticleResourceFilter constructor.
     * @param ArticleRepository $articleRepository
     */
    public function __construct(ArticleRepository $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }

    /**
     * @param ArticleFilter $filter
     * @return QueryBuilder
     */
    public function getResources($filter): QueryBuilder
    {
        $qb = $this->getQuery($filter);
        $qb->select('article');

        return $qb;
    }

    /**
     * @param ArticleFilter $filter
     * @return QueryBuilder
     */
    public function getResourceCount($filter): QueryBuilder
    {
        $qb = $this->getQuery($filter, 'count');
        $qb->select('count(article)');

        return $qb;
    }

    /**
     * @param ArticleFilter $filter
     * @param null|string $count
     * @return QueryBuilder
     */
    public function getQuery(ArticleFilter $filter, ?string $count = null): QueryBuilder
    {
        $qb = $this->articleRepository->createQueryBuilder('article');

        $qb->where('1=1');

        if (null !== $filter->getCategory()) {
            $qb->join('article.categories', 'article_categories');
            $qb->andwhere(
                $qb->expr()->eq('article_categories', ':article_categories')
            );
            $qb->setParameter('article_categories', $filter->getCategory());
        }

        if (null !== $filter->getTitle()) {
            $qb->andwhere(
                $qb->expr()->like('LOWER(article.title)', ':title')
            );
            $qb->setParameter('title', "%" . mb_strtolower($filter->getTitle()) . "%");
        }

        if (null !== $filter->getText()) {
            $qb->andwhere(
                $qb->expr()->like('article.text', ':text')
            );
            $qb->setParameter('text', "%{$filter->getText()}%");
        }

        if (null !== $filter->getCreatedFrom()) {
            $qb->andwhere(
                $qb->expr()->gte('article.created_at', ':createdFrom')
            );
            $qb->setParameter('createdFrom', $filter->getCreatedFrom());
        }

        if (null !== $filter->getCreatedTo()) {
            $qb->andWhere(
                $qb->expr()->lte('article.created_at', ':createdTo')
            );
            $qb->setParameter('createdTo', $filter->getCreatedTo());
        }

        if (null !== $filter->getUpdatedFrom()) {
            $qb->andWhere(
                $qb->expr()->gte('article.updated_at', ':updatedFrom')
            );
            $qb->setParameter('updatedFrom', $filter->getUpdatedFrom());
        }

        if (null !== $filter->getUpdatedTo()) {
            $qb->andWhere(
                $qb->expr()->lte('article.updated_at', ':updatedTo')
            );
            $qb->setParameter('updatedTo', $filter->getUpdatedTo());
        }

        if (null !== $filter->getSortByArray() && $count === null) {
            foreach ($filter->getSortByArray() as $by => $order) {
                $expr = 'desc' == $order
                    ? $qb->expr()->desc("article.$by")
                    : $qb->expr()->asc("article.$by");
                $qb->addOrderBy($expr);
            }
        }

        return $qb;
    }

}