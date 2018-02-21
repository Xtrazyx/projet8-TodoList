<?php
/**
 * Created by PhpStorm.
 * User: Xtrazyx
 * Date: 28/09/2017
 * Time: 17:42
 */

namespace Test\Entity;

use AppBundle\Entity\Task;
use PHPUnit\Framework\TestCase;

class TaskTest extends TestCase
{
    /**
     * @var Task
     */
    protected $testObject;

    public function setUp()
    {
        $this->testObject = new Task();
    }

    public function testGettersSetters()
    {
        // Mock Datetime class
        $createdAt = $this->createMock(\DateTime::class);

        $methodTests = array(
            'title' => 'Tester',
            'createdAt' => $createdAt,
            'content' => 'une tâche à exécuter en vitesse !'
        );

        // Testing getters and setters
        foreach ($methodTests as $key => $value)
        {
            $setMethod = 'set' . $key;
            $getMethod = 'get' . $key;
            $this->testObject->$setMethod($value);
            $this->assertEquals($value, $this->testObject->$getMethod());
        }

        // Testing for boolean getter
        $this->testObject->setIsDone(false);
        $this->assertEquals(false, $this->testObject->isDone());
    }

    public function testToggle()
    {
        // Initialize value
        $this->testObject->setIsDone(true);

        // Inverse the value of isDone
        $this->testObject->toggle();

        // Test isDone and Toggle
        $this->assertEquals(false, $this->testObject->isDone());
    }
}
