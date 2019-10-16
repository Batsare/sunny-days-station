<?php

namespace App\Manager;

use App\Entity\Menu;
use App\Form\MenuType;
use App\Repository\MenuRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\View\View;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class MenuManager
{
    private $em;

    private $validator;

    private $menuRepository;

    protected $request;

    private $form;

    /**
     * MenuManager constructor.
     * @param EntityManagerInterface $em
     * @param ValidatorInterface $validator
     * @param MenuRepository $menuRepository
     * @param RequestStack $requestStack
     * @param FormFactoryInterface $form
     */
    public function __construct(EntityManagerInterface $em,
                                ValidatorInterface $validator,
                                MenuRepository $menuRepository,
                                RequestStack $requestStack,
                                FormFactoryInterface $form)
    {
        $this->em = $em;
        $this->request = $requestStack->getCurrentRequest();
        $this->validator = $validator;
        $this->menuRepository = $menuRepository;
        $this->form = $form;
    }

    /**
     * @param int|null $idMenuItem
     * @return View
     */
    public function postOrUpdateMenuItemManager(?int $idMenuItem = null)
    {
        $menuItem = $idMenuItem ? $this->menuRepository->find($idMenuItem) : new Menu();
        $form = $this->form->create(MenuType::class, $menuItem);

        $form->submit($this->request->request->all());


        if ($form->isValid()) {

            $this->em->persist($menuItem);
            $this->em->flush();

            return View::create($menuItem,Response::HTTP_CREATED);

        } else {
            return View::create($form,Response::HTTP_NON_AUTHORITATIVE_INFORMATION);
        }
    }

    /**
     * @return View
     */
    public function getMenuItemManager(?int $idMenuItem)
    {
        $menuItem = $idMenuItem ? $this->menuRepository->find($idMenuItem) : $this->menuRepository->findBy([], ['position' => 'ASC']);
        return View::create($menuItem,Response::HTTP_OK);
    }

    /**
     * @param int $idMenuItem
     * @return View
     */
    public function deleteMenuItemManager(int $idMenuItem)
    {
        $menuItem = $this->menuRepository->find($idMenuItem);
        $this->em->remove($menuItem);
        $this->em->flush();
        return View::create('Item successfully deleted',Response::HTTP_OK);
    }
}
