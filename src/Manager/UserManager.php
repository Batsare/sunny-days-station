<?php
/**
 * Created by PhpStorm.
 * User: Dorian
 * Date: 02/03/2019
 * Time: 20:34
 */

namespace App\Manager;


use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\View\View;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserManager
{
    private $formBuilder;
    private $requestStack;
    private $passwordEncoder;
    private $em;
    private $token;

    public function __construct(FormFactoryInterface $formBuilder,
                                RequestStack $requestStack,
                                UserPasswordEncoderInterface $passwordEncoder,
                                EntityManagerInterface $em,
                                TokenStorageInterface $token)
    {
        $this->formBuilder = $formBuilder;
        $this->requestStack = $requestStack->getCurrentRequest();
        $this->passwordEncoder = $passwordEncoder;
        $this->em = $em;
        $this->token = $token;
    }

    public function postUserManager()
    {
        $user = new User();
        $form = $this->formBuilder->create(UserType::class, $user, ['validation_groups' => ['Default', 'New']]);

        $form->submit($this->requestStack->request->all());

        if ($form->isValid()) {
            // le mot de passe en claire est encodé avant la sauvegarde
            $encoded = $this->passwordEncoder->encodePassword($user, $user->getPassword());
            $user->setPassword($encoded);

            $this->em->persist($user);
            $this->em->flush();
            return View::create($user, Response::HTTP_CREATED);
        } else {
            return View::create($form, Response::HTTP_NON_AUTHORITATIVE_INFORMATION);
        }
    }

    public function updateUserManager($id, $clearMissing)
    {
        if((int)$id !== $this->token->getToken()->getUser()->getId()) {
            return View::create('Unauthorized access', Response::HTTP_UNAUTHORIZED);
        }

        $user = $this->em
            ->getRepository(User::class)
            ->find($id); // L'identifiant en tant que paramètre n'est plus nécessaire

        if (empty($user)) {
            return $this->userNotFound();
        }

        if ($clearMissing) {
            // Si une mise à jour complète, le mot de passe doit être validé
            $options = ['validation_groups' => ['Default', 'FullUpdate']];
        } else {
            $options = []; // Le groupe de validation par défaut de Symfony est Default
        }

//        $profilePicture = $this->requestStack->get('imageFile');
//        //TODO
//        //$base64Avatar = base64_decode($profilePicture);
//        $data = explode(',',$profilePicture);
//
//
////        $file = fopen('./img/test.jpg', 'wb');
////        fwrite($file,base64_decode(base64_decode($data[1])));
////        fclose($file);
//        $file = './img/test.jpg';
//        file_put_contents($file, base64_decode($data[1]));
//        $f = new UploadedFile($file, 'blabla');

        $form = $this->formBuilder->create(UserType::class, $user, $options);


        $form->submit($this->requestStack->request->all(), $clearMissing);

        if ($form->isValid()) {
            // Si l'utilisateur veut changer son mot de passe
            if (!empty($user->getPassword())) {
                $encoded = $this->passwordEncoder->encodePassword($user, $user->getPassword());
                $user->setPassword($encoded);
            }
            $this->em->merge($user);
            $this->em->flush();
            return View::create($user, Response::HTTP_OK);
        } else {
            return View::create($form, Response::HTTP_NON_AUTHORITATIVE_INFORMATION);
        }
    }

    private function userNotFound()
    {
        return View::create(['message' => 'User not found'], Response::HTTP_NOT_FOUND);
    }
}
