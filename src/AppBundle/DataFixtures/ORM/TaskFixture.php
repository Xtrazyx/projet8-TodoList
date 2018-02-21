<?php
/**
 * Created by PhpStorm.
 * User: Xtrazyx
 * Date: 09/01/2018
 * Time: 15:46
 */

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Task;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Yaml\Yaml;

class TaskFixture extends Fixture implements DependentFixtureInterface
{

    public function load(ObjectManager $manager)
    {
        $tasks = Yaml::parse(file_get_contents(dirname(__DIR__) . '/ORM/definitions/tasks.yaml'));

        foreach ($tasks as $taskData)
        {
            $task = new Task();
            // $user = $this->getReference($taskData['owner']); TODO implement relation

            $task->setTitle($taskData['title']);
            $task->setContent($taskData['content']);
            $task->setIsDone($taskData['isDone']);

            // Managing relation User->Task
            // $user->addTask($task); TODO implement relation

            $manager->persist($task);
            // $manager->persist($user); TODO implement relation
        }

        $manager->flush();
    }

    /*public function getDependencies()
    {
        return array(UserFixture::class);
    }*/
}
