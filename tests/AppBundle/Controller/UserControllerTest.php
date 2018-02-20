<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class UserControllerTest extends WebTestCase
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
        $crawler =  $this->client->request('GET', '/users/create');

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        // Form submit
        $form = $crawler->selectButton('Ajouter')->form();
        $form['user[username]'] = 'TestUser';
        $form['user[email]'] = 'test@user.com';
        $form['user[password][first]'] = 'test';
        $form['user[password][second]'] = 'test';
        $this->client->submit($form);

        // Test redirect response
        $this->assertTrue($this->client->getResponse()->isRedirect());

        $crawler = $this->client->followRedirect();

        // Test username field
        $this->assertGreaterThan(
            0,
            $crawler->filter('td:contains("TestUser")')->count()
        );

        // Test email field
        $this->assertGreaterThan(
            0,
            $crawler->filter('td:contains("test@user.com")')->count()
        );
    }

    public function testListAction()
    {
        $this->logIn();
        $crawler =  $this->client->request('GET', '/users');

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        // Test username field
        $this->assertGreaterThan(
            0,
            $crawler->filter('td:contains("TestUser")')->count()
        );

        // Test email field
        $this->assertGreaterThan(
            0,
            $crawler->filter('td:contains("test@user.com")')->count()
        );
    }

    public function testEditAction()
    {
        $this->logIn();
        $crawler =  $this->client->request('GET', '/users/1/edit');

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        // Form submit
        $form = $crawler->selectButton('Modifier')->form();
        $form['user[username]'] = 'TestUserEdit';
        $form['user[email]'] = 'test@useredit.com';
        $form['user[password][first]'] = 'test';
        $form['user[password][second]'] = 'test';
        $this->client->submit($form);

        // Test redirect response
        $this->assertTrue($this->client->getResponse()->isRedirect());

        $crawler = $this->client->followRedirect();

        // Test username field
        $this->assertGreaterThan(
            0,
            $crawler->filter('td:contains("TestUserEdit")')->count()
        );

        // Test email field
        $this->assertGreaterThan(
            0,
            $crawler->filter('td:contains("test@useredit.com")')->count()
        );
    }

}
