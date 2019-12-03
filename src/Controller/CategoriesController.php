<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Category;
use App\Entity\EntityMerger;
use App\Exception\ValidationException;
use App\Resource\Filtering\Article\ArticleFilterFactory;
use App\Resource\Filtering\Category\CategoryFilterFactory;
use App\Resource\Pagination\Article\ArticlePagination;
use App\Resource\Pagination\Category\CategoryPagination;
use App\Resource\Pagination\PageRequestFactory;
use FOS\RestBundle\Controller\ControllerTrait;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use App\Annotation\IgnoreSoftDelete;
use App\Listener\UndeletableInterface;

/**
 * Class CategoriesController
 * @package App\Controller
 * @Security("is_anonymous() or is_granted('ROLE_ADMIN')")
 *
 */
class CategoriesController extends AbstractController implements UndeletableInterface
{
    use ControllerTrait;

    /** @var CategoryPagination  */
    private $categoryPagination;

    /** @var ArticlePagination  */
    private $articlePagination;

    /**
     * @var EntityMerger
     */
    private $entityMerger;

    public function __construct(
        EntityMerger $entityMerger,
        CategoryPagination $categoryPagination,
        ArticlePagination $articlePagination
    )
    {
        $this->entityMerger = $entityMerger;
        $this->categoryPagination = $categoryPagination;
        $this->articlePagination = $articlePagination;
    }

    /**
     * Get all possible categories
     *
     * @Rest\View()
     * @Rest\Get("/categories", name="get_categories")
     *
     * @param Request $request
     * @return \Hateoas\Representation\PaginatedRepresentation
     */
    public function getCategories(Request $request)
    {
        $pageRequestFactory = new PageRequestFactory();
        $page = $pageRequestFactory->fromRequest($request);

        $categoryFilterFactory = new CategoryFilterFactory();
        $categoryFilter = $categoryFilterFactory->factory($request);

        return $this->categoryPagination->paginate($page,$categoryFilter);
    }

    /**
     * Get all deleted categories
     *
     * @Rest\View()
     * @Rest\Get("/categories/deleted", name="get_categories_deleted")
     * @IgnoreSoftDelete
     *
     * @return Category[]
     */
    public function getCategoriesDeleted()
    {
        $categories = $this->getDoctrine()->getRepository('App:Category')->findDeleted();

        return $categories;
    }

    /**
     * Get a single category
     *
     * @Rest\View()
     * @Rest\Get("/categories/{category}")
     * @param Category|null $category
     * @return Category|\FOS\RestBundle\View\View
     */
    public function getCategory(?Category $category)
    {
        if (null === $category) {
            return $this->view(null, 404);
        }

        return $category;
    }

    /**
     * Get category articles
     *
     * @Rest\View()
     * @Rest\Get("/categories/{category}/articles")
     * @param Category|null $category
     * @return Article[]|\FOS\RestBundle\View\View
     */
    public function getCategoryArticles(?Category $category)
    {
        if (null === $category) {
            return $this->view(null, 404);
        }

        return $category->getArticles();

    }

    /**
     * Post a new category
     *
     * @Security("is_granted('ROLE_ADMIN')")
     * @Rest\View()
     * @ParamConverter("category", converter="fos_rest.request_body")
     * @Rest\Post("/categories")
     * @param Category $category
     * @return Category
     */
    public function addCategories(Category $category, ConstraintViolationListInterface $validationErrors)
    {
        if (count($validationErrors) > 0) {
            throw new ValidationException($validationErrors);
        }
        $manager = $this->getDoctrine()->getManager();

        $manager->persist($category);
        $manager->flush();

        return $category;
    }

    /**
     * Delete a single category
     *
     * @Security("is_granted('ROLE_ADMIN')")
     * @Rest\View()
     * @Rest\Delete("/categories/{category}")
     * @param Category|null $category
     * @return array|\FOS\RestBundle\View\View
     */
    public function deleteCategory(?Category $category)
    {
        if (null === $category) {
            return $this->view(null, 404);
        }

        $manager = $this->getDoctrine()->getManager();
        $manager->remove($category);
        $manager->flush();

        return ['success' => true];
    }

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     * @Rest\View()
     * @Rest\Post("/categories/{category}/articles", name="post_category_article")
     * @ParamConverter("article", converter="fos_rest.request_body")
     *
     * @param Category $category
     * @param Article $article
     * @param ConstraintViolationListInterface $validationErrors
     * @return Category
     */
    public function addCategoryArticle(Category $category, Article $article, ConstraintViolationListInterface $validationErrors)
    {
        if (count($validationErrors) > 0) {
            throw new ValidationException($validationErrors);
        }

        $category->addArticle($article);

        $manager = $this->getDoctrine()->getManager();
        $manager->persist($category);
        $manager->flush();

        return $article;
    }

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     * @Rest\View()
     * @Rest\Delete("/categories/{category}/articles", name="post_category_article")
     * @ParamConverter("article", converter="fos_rest.request_body")
     *
     * @param Category $category
     * @param Article $article
     * @param ConstraintViolationListInterface $validationErrors
     * @return Article
     */
    public function deleteCategoryArticle(Category $category, Article $article, ConstraintViolationListInterface $validationErrors)
    {
        if (count($validationErrors) > 0) {
            throw new ValidationException($validationErrors);
        }

        $category->removeArticle($article);

        $manager = $this->getDoctrine()->getManager();
        $manager->persist($category);
        $manager->flush();

        return $article;
    }

    /**
     * Edit Category
     *
     * @Security("is_granted('ROLE_ADMIN')")
     * @Rest\View()
     * @Rest\Put("/categories/{category}", name="put_category")
     * @ParamConverter("newCategory", converter="fos_rest.request_body")
     * @IgnoreSoftDelete
     *
     * @param Category|null $category
     * @param Category $newCategory
     * @param ConstraintViolationListInterface $validationErrors
     * @return Category|\FOS\RestBundle\View\View|null
     * @throws \Exception
     */
    public function editCategory(?Category $category, Category $newCategory, ConstraintViolationListInterface $validationErrors)
    {
        if (null === $category) {
            return $this->view(null, 404);
        }

        if (count($validationErrors) > 0) {
            throw new ValidationException($validationErrors);
        }

        $this->entityMerger->merge($category, $newCategory);

        $manager = $this->getDoctrine()->getManager();
        $manager->persist($category);
        $manager->flush();

        return $category;
    }

    public function getEntityManagerPublic(){
        return $this->getDoctrine()->getManager();
    }
}