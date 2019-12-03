<?php

namespace App\Resource\Filtering\Category;

use App\Resource\Filtering\AbstractFilter;
use App\Resource\Filtering\FilterInterface;
use App\Resource\Filtering\FilterSortInterface;

class CategoryFilter
    extends AbstractFilter
    implements FilterInterface, FilterSortInterface
{
    /**
     * @var null|int
     */
    private $articles;

    /**
     * @var null|string
     */
    private $title;

    /**
     * @var array|null
     */
    private $sortByArray;

    /**
     * @var null|string
     */
    private $sortBy;

    /**
     * MovieFilterDefinition constructor.
     * @param null|int $category
     * @param null|string $title
     * @param null|string $sortByQuery
     * @param array|null $sortByArray
     */
    public function __construct(
        ?int $articles,
        ?string $title,
        ?string $sortByQuery,
        ?array $sortByArray
    )
    {
        $this->articles = $articles;
        $this->title = $title;
        $this->sortBy = $sortByQuery;
        $this->sortByArray = $sortByArray;
    }

    /**
     * @return int|null
     */
    public function getArticles(): ?int
    {
        return $this->articles;
    }

    /**
     * @param int|null $articles
     */
    public function setArticles(?int $articles): void
    {
        $this->articles = $articles;
    }

    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string|null $title
     */
    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return array|null
     */
    public function getSortByArray(): ?array
    {
        return $this->sortByArray;
    }

    /**
     * @param array|null $sortByArray
     */
    public function setSortByArray(?array $sortByArray): void
    {
        $this->sortByArray = $sortByArray;
    }

    /**
     * @return string|null
     */
    public function getSortBy(): ?string
    {
        return $this->sortBy;
    }

    /**
     * @param string|null $sortBy
     */
    public function setSortBy(?string $sortBy): void
    {
        $this->sortBy = $sortBy;
    }

    /**
     * @return null|string
     */
    public function getSortByQuery(): ?string
    {
        return $this->sortBy;
    }

    /**
     * @return array
     */
    public function getParameters(): array
    {
        return get_object_vars($this);
    }
}