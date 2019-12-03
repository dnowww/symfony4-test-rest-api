<?php

namespace App\Resource\Filtering;

interface FilterInterface
{
    /**
     * @return array
     */
    public function getQueryParameters(): array;

    /**
     * @return array
     */
    public function getQueryParamsBlacklist(): array;

    /**
     * @return array
     */
    public function getParameters(): array;
}