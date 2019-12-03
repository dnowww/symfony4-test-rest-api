<?php

namespace App\Resource\Filtering\Category;

use App\Resource\Filtering\AbstractFilterFactory;
use App\Resource\Filtering\FilterFactoryInterface;
use Symfony\Component\HttpFoundation\Request;

class CategoryFilterFactory
    extends AbstractFilterFactory
    implements FilterFactoryInterface
{
    private const KEY_ARTICLES = 'articles';
    private const KEY_TITLE = 'name';
    private const KEY_SORT_BY_QUERY = 'sortBy';
    private const KEY_SORT_BY_ARRAY = 'sortBy';
    private CONST ACCEPTED_SORT_FIELDS = ['id', 'name', 'created_at', 'updated_at'];

    /**
     * @param Request $request
     * @return CategoryFilter
     */
    public function factory(Request $request): CategoryFilter
    {
        return new CategoryFilter(
            $request->get(self::KEY_ARTICLES),
            $request->get(self::KEY_TITLE),
            $request->get(self::KEY_SORT_BY_QUERY),
            $this->sortQueryToArray($request->get(self::KEY_SORT_BY_ARRAY))
        );
    }

    /**
     * @return array
     */
    public function getAcceptedSortField(): array
    {
        return self::ACCEPTED_SORT_FIELDS;
    }
}