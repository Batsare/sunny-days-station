<?php
namespace App\Controller;

use App\Manager\UserManager;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest; // alias pour toutes les annotations

/**
 * Class UserController
 * @package App\Controller
 * @Rest\Route("/users")
 */
class UserController extends AbstractFOSRestController
{
    private $userManager;

    public function __construct(UserManager $userManager)
    {
        $this->userManager = $userManager;
    }

    /**
     * @Rest\Post("/")
     */
    public function postUsersAction()
    {
        return $this->userManager->postUserManager();
    }

    /**
     * @Rest\Put("/{id}")
     */
    public function updateUserAction($id)
    {
        return $this->userManager->updateUserManager($id, true);
    }

    /**
     * @Rest\Patch("/{id}")
     */
    public function patchUserAction($id)
    {
        return $this->userManager->updateUserManager($id, false);
    }
}