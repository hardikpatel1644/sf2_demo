<?php

namespace Hp\UserBundle\DataFixutres\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Hp\UserBundle\Entity\Role;

/**
 * Description of RoleFixtures
 *
 * @author Hardik Patel <hardikpatel1644@gmail.com>
 */

class RoleFixtures extends AbstractFixture implements OrderedFixtureInterface
{
    public function getOrder()
    {
        return 1;
    }

    public function load(ObjectManager $manager)
    {
        $roleAdmin = new Role();
        $roleAdmin->setName('admin');
        $roleAdmin->setRole('ROLE_ADMIN');
        $manager->persist($roleAdmin);
        
        $roleUser = new Role();
        $roleUser->setName('user');
        $roleUser->setRole('ROLE_USER');
        $manager->persist($roleUser);
        
        $manager->flush();
        
        $this->addReference('role_admin', $roleAdmin);
        $this->addReference('role_user', $roleUser);
    }
}