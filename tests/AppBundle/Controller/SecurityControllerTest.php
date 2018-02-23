<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class SecurityControllerTest extends WebTestCase
{
    /**
     * @var Client
     */
    private $client;

    public function setUp()
    {
        $this->client = static::createClient();
    }

    public function testLoginRoleUserAction()
    {
        $crawler =  $this->client->request('GET', '/login');

        $form = $crawler->selectButton('Se connecter')->form();
        $form['_username'] = 'user_with_role_user';
        $form['_password'] = 'user';

        $this->client->submit($form);

        // Redirect response and follow
        $this->assertTrue($this->client->getResponse()->isRedirect());
        $this->client->followRedirect();

        // Testing response and authentification
        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertTrue(
            $this
                ->client
                ->getContainer()
                ->get('security.authorization_checker')
                ->isGranted('ROLE_USER')
        );
    }

    public function testLoginRoleAdminAction()
    {
        $crawler =  $this->client->request('GET', '/login');

        $form = $crawler->selectButton('Se connecter')->form();
        $form['_username'] = 'user_with_role_admin';
        $form['_password'] = 'admin';

        $this->client->submit($form);

        // Redirect response and follow
        $this->assertTrue($this->client->getResponse()->isRedirect());
        $this->client->followRedirect();

        // Testing response and authentification
        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertTrue(
            $this
                ->client
                ->getContainer()
                ->get('security.authorization_checker')
                ->isGranted('ROLE_ADMIN')
        );
    }

    public function testLoginBadCredentialsAction()
    {
        $crawler =  $this->client->request('GET', '/login');

        $form = $crawler->selectButton('Se connecter')->form();
        $form['_username'] = 'bad_credentials';
        $form['_password'] = 'bad_password';

        $this->client->submit($form);

        // Redirect response and follow
        $this->assertTrue($this->client->getResponse()->isRedirect());
        $this->client->followRedirect();

        // Testing response and authentification
        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertTrue(
            $this
                ->client
                ->getContainer()
                ->get('security.authorization_checker')
                ->isGranted('IS_AUTHENTICATED_ANONYMOUSLY')
        );
    }
}
