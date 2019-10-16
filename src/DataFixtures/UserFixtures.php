<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 10; $i++) {
            $user = new User();
            $user->setFirstName('Jean'.$i);
            $user->setLastName('Dupont'.$i);
            $user->setEmail('testmail'.$i.'.@gmailcom');
            $user->setDateOfBirth(new \DateTime());
            $user->setPassword($this->passwordEncoder->encodePassword(
                $user,
                'password'
            ));
            $manager->persist($user);
        }

        $manager->flush();
    }
}
