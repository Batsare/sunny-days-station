<?php

namespace App\Controller;

use App\Manager\ArticleManager;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;

/**
 * Class ArticleController
 * @package App\Controller
 * @Rest\Route("/article")
 */
class ArticleController extends AbstractFOSRestController
{
    private $articleManager;

    public function __construct(ArticleManager $articleManager)
    {
        $this->articleManager = $articleManager;
    }

    /**
     * Creates an Article resource
     * @Rest\Post("/new")
     * @return ArticleManager|View
     * @throws \Exception
     */
    public function createArticle()
    {
        return $this->articleManager->createOrUpdateArticleManager();
    }

    /**
     * Creates an Article resource
     * @Rest\Put("/{idArticle}", requirements={"idArticle"="\d+"})
     * @param $idArticle
     * @return ArticleManager|View
     * @throws \Exception
     */
    public function updateArticle($idArticle)
    {
        return $this->articleManager->createOrUpdateArticleManager($idArticle);
    }

    /**
     * Retrieves an Article resource
     * @Rest\Get("/{idArticle}", requirements={"idArticle" = "\d+"}, defaults={"idArticle" = null})
     * @param int $idArticle
     * @return View
     */
    public function getArticle(int $idArticle = null): View
    {
        return $this->articleManager->getArticleManager($idArticle);
    }

    /**
     * Retrieves an Article resource
     * @Rest\Delete("/{idArticle}", requirements={"idArticle" = "\d+"})
     * @param int $idArticle
     * @return View
     */
    public function deleteArticle(int $idArticle): View
    {
        return $this->articleManager->deleteArticleManager($idArticle);
    }
}
