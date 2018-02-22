<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;

class SecurityControllerTest extends WebTestCase
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var AuthorizationChecker
     */
    private $authChecker;

    public function setUp()
    {
        $this->client = static::createClient();
        $this->authChecker = $this->client->getContainer()->get('security.authorization_checker');
    }

    public function testLoginRoleUserAction()
    {
        $crawler =  $this->client->request('GET', '/login');

        $form = $crawler->selectButton('Se connecter')->form();
        $form['_username'] = 'user_with_role_user';
        $form['_password'] = 'user';

        $this->client->submit($form);

        // Redirect response and follow
        $this->assertTrue($this->client->getResponse()->isRedirect(), $this->client->getResponse()->getContent());
        $this->client->followRedirect();

        // Testing response and authentification
        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertTrue($this->authChecker->isGranted('IS_AUTHENTICATED_ANONYMOUSLY'));
    }

    /*public function testLoginRoleAdminAction()
    {
        $crawler =  $this->client->request('GET', '/login');

        $form = $crawler->selectButton('Se connecter')->form();
        $form['_username'] = 'user_with_role_admin';
        $form['_password'] = 'admin';

        $this->client->submit($form);

        // Redirect response and follow
        $this->assertTrue($this->client->getResponse()->isRedirect(), $this->client->getResponse()->getContent());
        $this->client->followRedirect();

        // Testing response and authentification
        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertTrue($this->authChecker->isGranted('ROLE_ADMIN'));
    }*/

    public function testLoginBadCredentialsAction()
    {
        $crawler =  $this->client->request('GET', '/login');

        $form = $crawler->selectButton('Se connecter')->form();
        $form['_username'] = 'bad_credentials';
        $form['_password'] = 'bad_password';

        $this->client->submit($form);

        // Redirect response and follow
        $this->assertTrue($this->client->getResponse()->isRedirect(), $this->client->getResponse()->getContent());
        $this->client->followRedirect();

        // Testing response and authentification
        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertTrue($this->authChecker->isGranted('IS_AUTHENTICATED_ANONYMOUSLY'));
    }
}
