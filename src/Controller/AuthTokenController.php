<?php
/**
 * Created by PhpStorm.
 * User: Dorian
 * Date: 02/03/2019
 * Time: 22:10
 */

namespace App\Controller;


use App\Manager\AuthTokenManager;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest; // alias pour toutes les annotations

/**
 * Class AuthTokenController
 * @package App\Controller
 * @Rest\Route("/users")
 */
class AuthTokenController extends AbstractFOSRestController
{
    private $authTokenManager;

    public function __construct(AuthTokenManager $authTokenManager)
    {
        $this->authTokenManager = $authTokenManager;
    }

    /**
     * @Rest\Post("/auth-tokens")
     */
    public function postAuthTokensAction()
    {
        return $this->authTokenManager->postAuthTokensManager();
    }

    /**
     * @Rest\Delete("/auth-tokens/{id}")
     */
    public function removeAuthTokenAction($id)
    {
        return $this->authTokenManager->removeAuthTokenManager($id);
    }
}