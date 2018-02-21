<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class TaskControllerTest extends WebTestCase
{
    /**
     * @var Client
     */
    private $client;

    public function setUp()
    {
        $this->client = static::createClient();
    }

    // Create ROLE_USER logged in scenario
    private function logIn()
    {
        $session = $this->client->getContainer()->get('session');

        $firewallContext = 'main';

        $token = new UsernamePasswordToken('user', null, $firewallContext, array('ROLE_USER'));
        $session->set('_security_'.$firewallContext, serialize($token));
        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());
        $this->client->getCookieJar()->set($cookie);
    }

    public function testCreateAction()
    {
        $this->logIn();
        $crawler =  $this->client->request('GET', '/tasks/create');

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        // Form submit
        $form = $crawler->selectButton('Ajouter')->form();
        $form['task[title]'] = 'Hello';
        $form['task[content]'] = 'Contenu';
        $this->client->submit($form);

        // Test redirect response
        $this->assertTrue($this->client->getResponse()->isRedirect(), $this->client->getResponse()->getContent());

        $crawler = $this->client->followRedirect();

        // Test title field
        $this->assertGreaterThan(
            0,
            $crawler->filter('a:contains("Hello")')->count()
        );

        // Test content field
        $this->assertGreaterThan(
            0,
            $crawler->filter('p:contains("Contenu")')->count()
        );
    }

    public function testListDoneAction()
    {
        $this->logIn();
        $crawler =  $this->client->request('GET', '/tasks/done');

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        // Testing from fixtures
        $this->assertGreaterThan(
            0,
            $crawler->filter('a:contains("Test task1 owner.id=1")')->count()
        );

        $this->assertGreaterThan(
            0,
            $crawler->filter('a:contains("Test task3 owner.id=2")')->count()
        );
    }

    public function testListTodoAction()
    {
        $this->logIn();
        $crawler =  $this->client->request('GET', '/tasks/todo');

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        // Testing from fixtures
        $this->assertGreaterThan(
            0,
            $crawler->filter('a:contains("Test task2 owner.id=1")')->count()
        );

        $this->assertGreaterThan(
            0,
            $crawler->filter('a:contains("Test task4 owner.id=2")')->count()
        );
    }

    public function testEditAction()
    {
        $this->logIn();
        $crawler =  $this->client->request('GET', '/tasks/5/edit');

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        // Form submit
        $form = $crawler->selectButton('Modifier')->form();
        $form['task[title]'] = 'Yoyo';
        $form['task[content]'] = 'Popopo';
        $this->client->submit($form);

        // Test redirect response
        $this->assertTrue($this->client->getResponse()->isRedirect(), $this->client->getResponse()->getContent());

        $crawler = $this->client->followRedirect();

        // Test title field
        $this->assertGreaterThan(
            0,
            $crawler->filter('a:contains("Yoyo")')->count()
        );

        // Test content field
        $this->assertGreaterThan(
            0,
            $crawler->filter('p:contains("Popopo")')->count()
        );
    }

    public function testToggleAction()
    {
        $this->logIn();
        $this->client->request('GET', '/tasks/1/toggle');

        // Test redirect response
        $this->assertTrue($this->client->getResponse()->isRedirect());
    }

    public function testDeleteAction()
    {
        $this->logIn();
        $this->client->request('GET', '/tasks/1/delete');

        // Test redirect response
        $this->assertTrue($this->client->getResponse()->isRedirect());
    }

}
