<?php

namespace App\Resource\Filtering\Article;

use App\Resource\Filtering\AbstractFilterFactory;
use App\Resource\Filtering\FilterFactoryInterface;
use Symfony\Component\HttpFoundation\Request;

class ArticleFilterFactory
    extends AbstractFilterFactory
    implements FilterFactoryInterface
{
    private const KEY_CATEGORY = 'category';
    private const KEY_TITLE = 'title';
    private const KEY_TEXT = 'text';
    private const KEY_CREATED_FROM = 'createdFrom';
    private const KEY_CREATED_TO = 'createdTo';
    private const KEY_UPDATED_FROM = 'updatedFrom';
    private const KEY_UPDATED_TO = 'updatedTo';
    private const KEY_SORT_BY_QUERY = 'sortBy';
    private const KEY_SORT_BY_ARRAY = 'sortBy';
    private CONST ACCEPTED_SORT_FIELDS = ['id', 'title', 'created_at', 'updated_at'];

    /**
     * @param Request $request
     * @return ArticleFilter
     */
    public function factory(Request $request): ArticleFilter
    {
        return new ArticleFilter(
            $request->get(self::KEY_CATEGORY),
            $request->get(self::KEY_TITLE),
            $request->get(self::KEY_TEXT),
            $request->get(self::KEY_CREATED_FROM),
            $request->get(self::KEY_CREATED_TO),
            $request->get(self::KEY_UPDATED_FROM),
            $request->get(self::KEY_UPDATED_TO),
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