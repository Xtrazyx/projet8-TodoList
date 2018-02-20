<?php
/**
 * Created by PhpStorm.
 * User: Xtrazyx
 * Date: 28/09/2017
 * Time: 17:42
 */

namespace Test\Entity;

use AppBundle\Entity\User;
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
        $methodTests = array(
            'username' => 'Jojo666',
            'password' => '1234',
            'email' => 'roberto@jojo.com'
        );

        // Testing getters and setters
        foreach ($methodTests as $key => $value)
        {
            $setMethod = 'set' . $key;
            $getMethod = 'get' . $key;
            $this->testObject->$setMethod($value);
            $this->assertEquals($value, $this->testObject->$getMethod());
        }
    }
}
