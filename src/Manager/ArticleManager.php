<?php
/**
 * Created by PhpStorm.
 * User: Axel
 * Date: 18/02/19
 * Time: 20:33
 */

namespace App\Manager;


use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\View\View;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ArticleManager
{
    private $em;
    private $request;
    private $articleRepository;
    private $validator;
    private $form;

    /**
     * ArticleManager constructor.
     * @param EntityManagerInterface $em
     * @param RequestStack $request
     * @param ArticleRepository $articleRepository
     * @param ValidatorInterface $validator
     * @param FormFactoryInterface $form
     */
    public function __construct(EntityManagerInterface $em,
                                RequestStack $request,
                                ArticleRepository $articleRepository,
                                ValidatorInterface $validator,
                                FormFactoryInterface $form)
    {
        $this->em = $em;
        $this->request = $request->getCurrentRequest();
        $this->articleRepository = $articleRepository;
        $this->validator = $validator;
        $this->form = $form;
    }

    /**
     * @param int|null $idArticle
     * @return View
     * @throws \Exception
     */
    public function createOrUpdateArticleManager(?int $idArticle = null) :View
    {
        $article = $idArticle ? $this->articleRepository->find($idArticle) : new Article();
        $form = $this->form->create(ArticleType::class,$article);

        $form->submit($this->request->request->all());

        if ($form->isValid()) {

            $this->em->merge($article);
            $this->em->flush();

            return View::create($article, Response::HTTP_OK);

        } else {
            return View::create($form,Response::HTTP_BAD_REQUEST);
        }
    }


    /**
     * @param int $idArticle
     * @return View
     */
    public function getArticleManager(int $idArticle = null) : View
    {
        $article = $idArticle ? $this->articleRepository->find($idArticle) : $this->articleRepository->findBy([], ['id' => 'DESC']);

        return View::create($article,Response::HTTP_OK);

    }

    /**
     * @param int $idArticle
     * @return View
     */
    public function deleteArticleManager(int $idArticle) : View
    {
        $article = $this->articleRepository->find($idArticle);
        $this->em->remove($article);
        $this->em->flush();

        return View::create('Article successfully deleted',Response::HTTP_OK);

    }
}
