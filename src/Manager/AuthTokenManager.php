<?php
/**
 * Created by PhpStorm.
 * User: Dorian
 * Date: 02/03/2019
 * Time: 21:59
 */

namespace App\Manager;

use App\Entity\AuthToken;
use App\Entity\Credentials;
use App\Entity\User;
use App\Form\CredentialsType;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\View\View;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AuthTokenManager
{
    private $request;
    private $form;
    private $em;
    private $passwordEncoder;
    private $tokenStorage;

    public function __construct(RequestStack $requestStack,
                                FormFactoryInterface $formFactory,
                                EntityManagerInterface $entityManager,
                                UserPasswordEncoderInterface $userPasswordEncoder,
                                TokenStorageInterface $tokenStorage)
    {
        $this->request = $requestStack->getCurrentRequest();
        $this->form = $formFactory;
        $this->em = $entityManager;
        $this->passwordEncoder = $userPasswordEncoder;
        $this->tokenStorage = $tokenStorage;
    }

    public function postAuthTokensManager()
    {
        $credentials = new Credentials();
        $form = $this->form->create(CredentialsType::class, $credentials);

        $form->submit($this->request->request->all());

        if (!$form->isValid()) {
            return View::create($form, Response::HTTP_NON_AUTHORITATIVE_INFORMATION);
        }

        $user = $this->em->getRepository(User::class)
            ->findOneBy(['email' => $credentials->getLogin()]);

        if (!$user) { // L'utilisateur n'existe pas
            return View::create($this->invalidCredentials(),Response::HTTP_BAD_REQUEST);
        }

        $isPasswordValid = $this->passwordEncoder->isPasswordValid($user, $credentials->getPassword());

        if (!$isPasswordValid) { // Le mot de passe n'est pas correct
            return View::create($this->invalidCredentials(),Response::HTTP_BAD_REQUEST);
        }

        $authToken = new AuthToken();
        try {
            $authToken->setValue(base64_encode(random_bytes(50)));
            $authToken->setCreatedAt(new \DateTime('now'));
        } catch (\Exception $e) {
            return View::create($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        $authToken->setUser($user);

        $this->em->persist($authToken);
        $this->em->flush();

        return View::create($authToken, Response::HTTP_CREATED);
    }

    private function invalidCredentials()
    {
        return View::create(['message' => 'Invalid credentials'], Response::HTTP_BAD_REQUEST);
    }

    public function removeAuthTokenManager($id)
    {
        $authToken = $this->em->getRepository(AuthToken::class)
            ->find($id);

        $connectedUser = $this->tokenStorage->getToken()->getUser();

        if ($authToken && $authToken->getUser() === $connectedUser) {
            $this->em->remove($authToken);
            $this->em->flush();
            return View::create(null,Response::HTTP_NO_CONTENT);
        } else {
            throw new \Symfony\Component\HttpKernel\Exception\BadRequestHttpException();
        }
    }


}