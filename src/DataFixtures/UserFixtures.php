<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

use App\Entity\User;
use App\Entity\Structure;
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
        $usernames = [
          'John Doe',
          'Jane Doe',
          'Jason Doe',
          'Jenny Doe',
          'Jack Doe',
          'Jessica Doe',
          'James Doe',
          'Julia Doe'
        ];

        $password = 'Dø€På∫∫!';

        $structures = [
          'My First Structure',
          'My Second Structure',
          'My Third Structure',
          'My Fourth Structure'
        ];

        $roles = [
          'ROLE_SUPERADMIN',
          'ROLE_ADMIN',
          'ROLE_MANAGER',
          'ROLE_USER'
        ];

        // set data
        $structure = new Structure();
        $structure->setName($structures[0]);

        $user = new User();
        $user->setUsername($usernames[0]);
        $user->setPassword($this->passwordEncoder->encodePassword(
            $user,
            $password . '1'
        ));
        $user->setRoles(array($roles[1]));

        // relate this user to the structure
        $user->setStructure($structure);

        $manager->persist($structure);
        $manager->persist($user);

        $manager->flush();
    }
}
