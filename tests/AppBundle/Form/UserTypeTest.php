<?php
/**
 * Created by PhpStorm.
 * User: Xtrazyx
 * Date: 04/10/2017
 * Time: 12:31
 */

namespace App\Tests\Form;

use AppBundle\Form\UserType;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Form\Form;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserTypeTest extends TypeTestCase
{
    private $validator;
    private $authChecker;

    protected function setUp()
    {
        $this->authChecker = $this
            ->createMock(AuthorizationCheckerInterface::class)
        ;

        parent::setUp();
    }

    protected function getExtensions()
    {
        $type = new UserType($this->authChecker);

        $this->validator = $this->createMock(ValidatorInterface::class);
        $this->validator
            ->method('validate')
            ->will($this->returnValue(new ConstraintViolationList()));
        $this->validator
            ->method('getMetadataFor')
            ->will($this->returnValue(new ClassMetadata(Form::class)));

        return array(
            new PreloadedExtension(array($type), array()),
            new ValidatorExtension($this->validator),
        );
    }

    public function testValidFormData()
    {
        $formData = array(
            'username' => 'SuperJojo',
            'email' => 'jojo@asticot.fr',
            'password' => null
        );

        $form = $this->factory->create(UserType::class);

        $form->submit($formData);

        // Testing values through data transformers
        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($formData, $form->getData());

        // Testing form elements in view
        $view = $form->createView();
        $children = $view->children;

        foreach(array_keys($formData) as $key)
        {
            $this->assertArrayHasKey($key, $children);
        }
    }
}
