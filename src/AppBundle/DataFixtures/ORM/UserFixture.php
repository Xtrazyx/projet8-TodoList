<?php
/**
 * Created by PhpStorm.
 * User: Xtrazyx
 * Date: 09/01/2018
 * Time: 15:46
 */

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Yaml\Yaml;

class UserFixture extends Fixture implements ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    // UserPasswordEncoderInterface $encoder

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        $users = Yaml::parse(file_get_contents(dirname(__DIR__) . '/ORM/definitions/users.yaml'));

        foreach ($users as $key => $value)
        {
            $user = new User();

            $user->setUsername($key);
            $user->setEmail($value['email']);
            $user->setPassword(
                $this->container->get('security.password_encoder')->encodePassword($user, $value['password'])
            );

            $this->addReference($key, $user);

            $manager->persist($user);
        }

        $manager->flush();
    }
}
