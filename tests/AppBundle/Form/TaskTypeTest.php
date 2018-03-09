<?php
/**
 * Created by PhpStorm.
 * User: Xtrazyx
 * Date: 04/10/2017
 * Time: 12:31
 */

namespace App\Tests\Form;

use AppBundle\Form\TaskType;
use Symfony\Component\Form\Test\TypeTestCase;

class TaskTypeTest extends TypeTestCase
{
    public function testValidFormData()
    {
        $formData = array(
            'title' => 'Coucou',
            'content' => 'Bonjour bonsoir, ça va ?'
        );

        $form = $this->factory->create(TaskType::class);

        $form->submit($formData);

        // Testing values through data transformers
        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($formData, $form->getData());

        // Testing form elements in view
        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }
}
