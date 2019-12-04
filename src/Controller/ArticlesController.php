<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Category;
use App\Entity\EntityMerger;
use App\Exception\ValidationException;
use App\Listener\UndeletableInterface;
use App\Resource\Filtering\Article\ArticleFilterFactory;
use App\Resource\Pagination\Article\ArticlePagination;
use App\Resource\Pagination\PageRequestFactory;
use FOS\RestBundle\Controller\ControllerTrait;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use App\Annotation\IgnoreSoftDelete;

/**
 * Class ArticlesController
 * @package App\Controller
 * @Security("is_anonymous() or is_granted('ROLE_ADMIN')")
 *
 */
class ArticlesController extends AbstractController implements UndeletableInterface
{
    use ControllerTrait;

    /**
     * @var
     */
    private $articlePagination;

    /**
     * @var EntityMerger
     */
    private $entityMerger;

    public function __construct(
        EntityMerger $entityMerger,
        ArticlePagination $articlePagination
    )
    {
        $this->entityMerger = $entityMerger;
        $this->articlePagination = $articlePagination;
    }

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     * @Rest\View()
     * @Rest\Post("/articles/{article}/categories", name="post_article_category")
     * @ParamConverter("category", converter="fos_rest.request_body")
     *
     * @param Article $article
     * @param Category $category
     * @param ConstraintViolationListInterface $validationErrors
     * @return []|Category
     */
    public function addArticleCategory(Article $article, Category $category, ConstraintViolationListInterface $validationErrors)
    {
        if (count($validationErrors) > 0) {
            throw new ValidationException($validationErrors);
        }


        $article->addCategory($category);

        $manager = $this->getDoctrine()->getManager();
        $manager->persist($article);
        $manager->flush();

        return $article;
    }

    /**
     * @Security("is_granted('ROLE_ADMIN')")
     * @Rest\View()
     * @Rest\Delete("/articles/{article}/categories", name="delete_article_category")
     * @ParamConverter("category", converter="fos_rest.request_body")
     *
     * @param Article $article
     * @param Category $category
     * @param ConstraintViolationListInterface $validationErrors
     * @return []|Category
     */
    public function deleteArticleCategory(Article $article, Category $category, ConstraintViolationListInterface $validationErrors)
    {
        if (count($validationErrors) > 0) {
            throw new ValidationException($validationErrors);
        }


        $article->removeCategory($category);

        $manager = $this->getDoctrine()->getManager();
        $manager->persist($article);
        $manager->flush();

        return $article;
    }

    /**
     * Get all active articles
     *
     * @Rest\View()
     * @Rest\Get("/articles", name="get_articles")
     *
     * @param Request $request
     * @return \Hateoas\Representation\PaginatedRepresentation
     */
    public function getArticles(Request $request)
    {
        $pageRequestFactory = new PageRequestFactory();
        $page = $pageRequestFactory->fromRequest($request);

        $articleFilterFactory = new ArticleFilterFactory();
        $articleFilter = $articleFilterFactory->factory($request);

        //$articles = $this->getDoctrine()->getRepository('App:Article')->findAll();

        return $this->articlePagination->paginate($page,$articleFilter);
    }

    /**
     * Get all deleted articles
     *
     * @Rest\View()
     * @Rest\Get("/articles/deleted", name="get_articles_deleted")
     * @IgnoreSoftDelete
     *
     * @return Article[]
     */
    public function getArticlesDeleted()
    {
        $articles = $this->getDoctrine()->getRepository('App:Article')->findDeleted();

        return $articles;
    }

    /**
     * Get a single article
     *
     * @Rest\View()
     * @Rest\Get("/articles/{article}")
     * @param Article|null $article
     * @return Article|\FOS\RestBundle\View\View
     */
    public function getArticle(?Article $article)
    {
        if (null === $article) {
            return $this->view(null, 404);
        }

        return $article;
    }

    /**
     * Get article categories
     *
     * @Rest\View()
     * @Rest\Get("/articles/{article}/categories")
     * @param Article|null $article
     * @return Category[]|\FOS\RestBundle\View\View
     */
    public function getArticleCategories(?Article $article)
    {
        if (null === $article) {
            return $this->view(null, 404);
        }

        return $article->getCategories();
    }

    /**
     * Post a new article
     *
     * @Security("is_granted('ROLE_ADMIN')")
     * @Rest\View()
     * @ParamConverter("article", converter="fos_rest.request_body")
     * @Rest\Post("/articles")
     * @param Article $article
     * @return Article
     */
    public function addArticles(Article $article, ConstraintViolationListInterface $validationErrors)
    {
        if (count($validationErrors) > 0) {
            throw new ValidationException($validationErrors);
        }
        $manager = $this->getDoctrine()->getManager();

        $manager->persist($article);
        $manager->flush();

        return $article;
    }

    /**
     * Delete a single article
     *
     * @Security("is_granted('ROLE_ADMIN')")
     * @Rest\View()
     * @Rest\Delete("/articles/{article}")
     * @param Article|null $article
     * @return array|\FOS\RestBundle\View\View
     */
    public function deleteArticle(?Article $article)
    {
        if (null === $article) {
            return $this->view(null, 404);
        }

        $manager = $this->getDoctrine()->getManager();
        $manager->remove($article);
        $manager->flush();

        return ['success' => true];
    }

    /**
     * Edit Article
     *
     * @Security("is_granted('ROLE_ADMIN')")
     * @Rest\View()
     * @Rest\Put("/articles/{article}", name="put_article")
     * @ParamConverter("newArticle", converter="fos_rest.request_body")
     * @IgnoreSoftDelete
     *
     * @param Article|null $article
     * @param Article $newArticle
     * @param ConstraintViolationListInterface $validationErrors
     * @return Article|\FOS\RestBundle\View\View|null
     * @throws \Exception
     */
    public function editArticle(?Article $article, Article $newArticle, ConstraintViolationListInterface $validationErrors)
    {
        if (null === $article) {
            return $this->view(null, 404);
        }

        if (count($validationErrors) > 0) {
            throw new ValidationException($validationErrors);
        }

        $this->entityMerger->merge($article, $newArticle);

        $manager = $this->getDoctrine()->getManager();
        $manager->persist($article);
        $manager->flush();

        return $article;
    }

    public function getEntityManagerPublic(){
        return $this->getDoctrine()->getManager();
    }

}