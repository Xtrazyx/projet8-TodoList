<?php
/**
 * Created by PhpStorm.
 * User: Xtrazyx
 * Date: 28/09/2017
 * Time: 17:42
 */

namespace Test\Entity;

use AppBundle\Entity\Task;
use AppBundle\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    /**
     * @var User
     */
    protected $testObject;

    public function setUp()
    {
        $this->testObject = new User();
    }

    public function testGettersSetters()
    {
        // Array collection for testing method operation
        $tasks = new ArrayCollection();
        $task = $this->createMock(Task::class);

        $methodTests = array(
            'Username' => 'Jojo666',
            'Password' => '1234',
            'Email' => 'roberto@jojo.com',
            'Tasks' => $tasks,
            'Roles' => ['ROLE_USER']
        );

        // Testing getters and setters
        foreach ($methodTests as $key => $value) {
            $setMethod = 'set' . ucfirst($key);
            $getMethod = 'get' . ucfirst($key);
            $this->testObject->$setMethod($value);
            $this->assertEquals($value, $this->testObject->$getMethod());
        }

        // Testing methods for adding removing task in tasks
        $this->testObject->addTask($task);
        $this->assertTrue($this->testObject->getTasks()->contains($task));
        $this->testObject->removeTask($task);
        $this->assertNotTrue($this->testObject->getTasks()->contains($task));
    }
}
