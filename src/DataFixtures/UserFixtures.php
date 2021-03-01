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

        $roles = [
          'ROLE_SUPERADMIN',
          'ROLE_ADMIN',
          'ROLE_MANAGER',
          'ROLE_USER'
        ];

        $structure_names = [
          'My 1st structure',
          'My 2nd structure',
          'My 3rd structure'
        ];

        // load structures data
        for ($i=0; $i < count($structure_names); $i++)
        {
          $structure = new Structure();
          $structure->setName($structure_names[$i]);
          $manager->persist($structure);
        }

        // save
        $manager->flush();

        // get all structures
        $structures = $manager->getRepository(Structure::class)->findAll();

        // load users data
        for ($i=0; $i < count($usernames); $i++)
        {
          $random_percentage = mt_rand(0, 100);
          $random_role = mt_rand(0, (count($roles) - 1));
          $random_structure = mt_rand(0, (count($structures) - 1));

          $user = new User();
          $user->setUsername($usernames[$i]);
          $user->setPassword($this->passwordEncoder->encodePassword(
              $user,
              $password . $i
          ));
          $user->setRoles(array(
            ($random_percentage > 60) ? $roles[$random_role] : $roles[3]
          ));

          // relate user to structure
          $user->setStructure($structures[$random_structure]);

          $manager->persist($user);
        }

        // save
        $manager->flush();
    }
}
