<?php

namespace App\Resource\Filtering;

interface FilterSortInterface
{
    /**
     * @return array|null
     */
    public function getSortByArray(): ?array;

    /**
     * @return null|string
     */
    public function getSortByQuery(): ?string;
}