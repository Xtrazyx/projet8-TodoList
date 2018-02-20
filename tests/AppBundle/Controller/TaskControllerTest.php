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
        $this->assertTrue($this->client->getResponse()->isRedirect());

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

    public function testListAction()
    {
        $this->logIn();
        $crawler =  $this->client->request('GET', '/tasks');

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

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

    public function testEditAction()
    {
        $this->logIn();
        $crawler =  $this->client->request('GET', '/tasks/1/edit');

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        // Form submit
        $form = $crawler->selectButton('Modifier')->form();
        $form['task[title]'] = 'Yoyo';
        $form['task[content]'] = 'Popopo';
        $this->client->submit($form);

        // Test redirect response
        $this->assertTrue($this->client->getResponse()->isRedirect());

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
