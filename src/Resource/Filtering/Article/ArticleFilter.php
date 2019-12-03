<?php

namespace App\Resource\Filtering\Article;

use App\Resource\Filtering\AbstractFilter;
use App\Resource\Filtering\FilterInterface;
use App\Resource\Filtering\FilterSortInterface;

class ArticleFilter
    extends AbstractFilter
    implements FilterInterface, FilterSortInterface
{
    /**
     * @var null|int
     */
    private $category;

    /**
     * @var null|string
     */
    private $title;
    /**
     * @var null|string
     */
    private $text;

    /**
     * @var int|null
     */
    private $createdFrom;

    /**
     * @var int|null
     */
    private $createdTo;

    /**
     * @var int|null
     */
    private $updatedFrom;

    /**
     * @var int|null
     */
    private $updatedTo;

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
     * @param null|string $text
     * @param int|null $createdFrom
     * @param int|null $createdTo
     * @param int|null $updatedFrom
     * @param int|null $updatedTo
     * @param null|string $sortByQuery
     * @param array|null $sortByArray
     */
    public function __construct(
        ?int $category,
        ?string $title,
        ?string $text,
        ?int $createdFrom,
        ?int $createdTo,
        ?int $updatedFrom,
        ?int $updatedTo,
        ?string $sortByQuery,
        ?array $sortByArray
    )
    {
        $this->category = $category;
        $this->title = $title;
        $this->text = $text;
        $this->createdFrom = $createdFrom;
        $this->createdTo = $createdTo;
        $this->updatedFrom = $updatedFrom;
        $this->updatedTo = $updatedTo;
        $this->sortBy = $sortByQuery;
        $this->sortByArray = $sortByArray;
    }

    /**
     * @return int|null
     */
    public function getCategory(): ?int
    {
        return $this->category;
    }

    /**
     * @param int|null $category
     */
    public function setCategory(?int $category): void
    {
        $this->category = $category;
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
     * @return string|null
     */
    public function getText(): ?string
    {
        return $this->text;
    }

    /**
     * @param string|null $text
     */
    public function setText(?string $text): void
    {
        $this->text = $text;
    }

    /**
     * @return int|null
     */
    public function getCreatedFrom(): ?int
    {
        return $this->createdFrom;
    }

    /**
     * @param int|null $createdFrom
     */
    public function setCreatedFrom(?int $createdFrom): void
    {
        $this->createdFrom = $createdFrom;
    }

    /**
     * @return int|null
     */
    public function getCreatedTo(): ?int
    {
        return $this->createdTo;
    }

    /**
     * @param int|null $createdTo
     */
    public function setCreatedTo(?int $createdTo): void
    {
        $this->createdTo = $createdTo;
    }

    /**
     * @return int|null
     */
    public function getUpdatedFrom(): ?int
    {
        return $this->updatedFrom;
    }

    /**
     * @param int|null $updatedFrom
     */
    public function setUpdatedFrom(?int $updatedFrom): void
    {
        $this->updatedFrom = $updatedFrom;
    }

    /**
     * @return int|null
     */
    public function getUpdatedTo(): ?int
    {
        return $this->updatedTo;
    }

    /**
     * @param int|null $updatedTo
     */
    public function setUpdatedTo(?int $updatedTo): void
    {
        $this->updatedTo = $updatedTo;
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