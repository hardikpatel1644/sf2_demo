<?php

namespace Hp\UserBundle\DataFixutres\ORM;

use Hp\UserBundle\Entity\User;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Description of UserFixutes
 *
 * @author Hardik Patel <hardikpatel1644@gmail.com>
 */
class UserFixutes extends AbstractFixture implements ContainerAwareInterface, OrderedFixtureInterface {

    private $container;

    public function setContainer(ContainerInterface $container = null) {
        $this->container = $container;
    }

    public function getOrder() {
        return 10;
    }

    public function load(ObjectManager $manager) {


        $adminUser = new \Hp\UserBundle\Entity\User();
        $adminUser->setEmail('admin@example.com');
        $adminUser->setUsername('admin');
        $adminUser->setSalt(md5(uniqid()));
        $encoder = $this->container
                ->get('security.encoder_factory')
                ->getEncoder($adminUser);
        $adminUser->setPassword($encoder->encodePassword('adminpass', $adminUser->getSalt()));
        $adminUser->setIsActive(true);
        $adminUser->addRole($this->getReference('role_admin'));
        $manager->persist($adminUser);

        $normalUser = new \Hp\UserBundle\Entity\User();
        $normalUser->setEmail('user@example.com');
        $normalUser->setUsername('user');
        $normalUser->setSalt(md5(uniqid()));
        $normalUser->setPassword($encoder->encodePassword('userpass', $normalUser->getSalt()));
        $normalUser->setIsActive(true);
        $normalUser->addRole($this->getReference('role_user'));
        $manager->persist($normalUser);
        $manager->flush();
    }

}
