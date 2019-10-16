<?php

namespace App\Controller;

use App\Manager\MenuManager;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class MenuController
 * @package App\Controller
 * @Rest\Route("/menu")
 */
class MenuController extends AbstractController
{
    private $menuManager;

    public function __construct(MenuManager $menuManager)
    {
        $this->menuManager = $menuManager;
    }

    /**
     * Creates a menu resource
     * @Rest\Post("/")
     * @return \FOS\RestBundle\View\View
     */
    public function postMenuItem()
    {
        return $this->menuManager->postOrUpdateMenuItemManager();
    }

    /**
     * Get menu resource
     * @Rest\Get("/{idMenuItem}", requirements={"idMenuItem" = "\d+"}, defaults={"idMenuItem" = null})
     * @param null $idMenuItem
     * @return \FOS\RestBundle\View\View
     */
    public function getMenuItem($idMenuItem = null)
    {
        return $this->menuManager->getMenuItemManager($idMenuItem);
    }

    /**
     * Update menu resource
     * @Rest\Put("/{idMenuItem}", requirements={"idMenuItem" = "\d+"})
     * @param $idMenuItem
     * @return \FOS\RestBundle\View\View
     */
    public function updateMenuItem($idMenuItem)
    {
        return $this->menuManager->postOrUpdateMenuItemManager($idMenuItem);
    }

    /**
     * Update menu resource
     * @Rest\Delete("/{idMenuItem}", requirements={"idMenuItem" = "\d+"})
     * @return \FOS\RestBundle\View\View
     */
    public function deleteMenuItem($idMenuItem)
    {
        return $this->menuManager->deleteMenuItemManager($idMenuItem);
    }

}
