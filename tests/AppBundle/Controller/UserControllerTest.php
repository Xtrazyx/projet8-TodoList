<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

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

    // Create logged in scenarios
    private function logIn($role = 'ROLE_USER')
    {
        if ($role == 'ROLE_USER') {
            $crawler =  $this->client->request('GET', '/login');

            $form = $crawler->selectButton('Se connecter')->form();
            $form['_username'] = 'user_with_role_user';
            $form['_password'] = 'user';

            $this->client->submit($form);
            return;
        }

        if ($role == 'ROLE_ADMIN') {
            $crawler =  $this->client->request('GET', '/login');

            $form = $crawler->selectButton('Se connecter')->form();
            $form['_username'] = 'user_with_role_admin';
            $form['_password'] = 'admin';

            $this->client->submit($form);
        }
    }



    public function testCreateAction()
    {
        $this->logIn('ROLE_ADMIN');
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
        $this->logIn('ROLE_ADMIN');
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

    public function testListAccessDenied()
    {
        $this->logIn('ROLE_USER');

        $crawler = $this->client->request('GET', '/users');

        // Test access denied
        $this->assertSame(Response::HTTP_FORBIDDEN, $this->client->getResponse()->getStatusCode());
        $this->assertGreaterThan(
            0,
            $crawler->filter('body:contains("Access Denied")')->count()
        );
    }

    public function testEditAction()
    {
        $this->logIn('ROLE_ADMIN');
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
