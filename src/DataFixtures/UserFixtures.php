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

    public function int_to_ordinal($i)
    {
      $j = $i % 10;
      $k = $i % 100;

      if ($j === 1 && $k !== 11) {
        return $i . 'st';
      }
      if ($j === 2 && $k !== 12) {
        return $i . 'nd';
      }
      if ($j === 3 && $k !== 13) {
        return $i . 'rd';
      }
      return $i . 'th';
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

        for ($i=0; $i < count($usernames); $i++)
        {
          $random_percentage = mt_rand(0, 100);
          $random_role = mt_rand(0, (count($roles) - 1));

          // set data
          $structure = new Structure();
          $structure->setName('My ' . $this->int_to_ordinal($i + 1) . ' structure');

          $user = new User();
          $user->setUsername($usernames[$i]);
          $user->setPassword($this->passwordEncoder->encodePassword(
              $user,
              $password . $i
          ));
          $user->setRoles(array(
            ($random_percentage > 60) ? $roles[$random_role] : $roles[3]
          ));

          // relate this user to the structure
          $user->setStructure($structure);

          // manage data
          $manager->persist($structure);
          $manager->persist($user);
        }

        // save data
        $manager->flush();
    }
}
