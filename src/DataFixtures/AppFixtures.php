<?php

namespace App\DataFixtures;

use App\Entity\Role;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class AppFixtures extends Fixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;
    /**
     * AppFixtures constructor.
     * @param UserPasswordEncoderInterface $userPasswordEncoder
     */
    public function __construct(UserPasswordEncoderInterface $userPasswordEncoder)
    {
        $this->encoder = $userPasswordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $roles = [
            "ROLE_USER" => "User",
            "ROLE_ADMINISTRATOR" => "Admin"
        ];

        foreach ($roles as $key => $value) {
            if (!$manager->getRepository(Role::class)->findByRoleName([$key])) {
                $role = new Role();
                $role->setRoleName($key);
                $role->setLibelle($value);
                $manager->persist($role);
                $manager->flush();
            }
        }

        $user = new User();
        $user2 = new User();
        if (!$manager->find(User::class, 1)) {
            $user->setUsername('admin');
            $user->setRoles(["ROLE_SUPERUSER"]);
            $user->setPassword($this->encoder->encodePassword($user, 'admin'));
            $user->setNomComplet('Admin');
            $user->setEmail('admin@example.com');
            $user->setValid(true);
            $user->setDeleted(false);
            $user->setAdmin(true);
            $user->setBirthday(new \DateTime());
            $user->setGender("Male");
            $user->setAddress("");
            $user->setPhoneNumber("+216 53585935");
            $manager->persist($user);
            $manager->flush();

            $user2->setUsername('user');
            $user2->setRoles(["ROLE_USER"]);
            $user2->setPassword($this->encoder->encodePassword($user2, 'user'));
            $user2->setNomComplet('User');
            $user2->setEmail('user@example.com');
            $user2->setValid(true);
            $user2->setDeleted(false);
            $user2->setAdmin(false);
            $user2->setBirthday(new \DateTime());
            $user2->setGender("Male");
            $user2->setAddress("");
            $user2->setPhoneNumber("+216 53585935");
            $manager->persist($user2);
            $manager->flush();
        }
    }
}
