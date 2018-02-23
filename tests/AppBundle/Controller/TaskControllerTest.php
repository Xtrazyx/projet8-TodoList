<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

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
    private function logIn($role = 'ROLE_USER')
    {
        if($role == 'ROLE_USER'){
            $crawler =  $this->client->request('GET', '/login');

            $form = $crawler->selectButton('Se connecter')->form();
            $form['_username'] = 'user_with_role_user';
            $form['_password'] = 'user';

            $this->client->submit($form);
            return;
        }

        if($role == 'ROLE_ADMIN'){
            $crawler =  $this->client->request('GET', '/login');

            $form = $crawler->selectButton('Se connecter')->form();
            $form['_username'] = 'user_with_role_admin';
            $form['_password'] = 'admin';

            $this->client->submit($form);
        }
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
            $crawler->filter('h4:contains("Test task3 owner.id=2")')->count()
        );
    }

    public function testListDoneAdminAction()
    {
        $this->logIn('ROLE_ADMIN');
        $crawler =  $this->client->request('GET', '/tasks/done');

        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        // Testing from fixtures
        $this->assertGreaterThan(
            0,
            $crawler->filter('h4:contains("Test task1 owner.id=1")')->count()
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
            $crawler->filter('h4:contains("Test task4 owner.id=2")')->count()
        );
    }

    public function testEditAction()
    {
        $this->logIn();
        $crawler =  $this->client->request('GET', '/tasks/2/edit');

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

    public function testEditAccessDeniedAction()
    {
        $this->logIn('ROLE_ADMIN');
        $this->client->request('GET', '/tasks/2/edit');

        $this->assertSame(Response::HTTP_FORBIDDEN, $this->client->getResponse()->getStatusCode());
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

    public function testDeleteAccessDeniedAction()
    {
        $this->logIn('ROLE_ADMIN');
        $this->client->request('GET', '/tasks/2/delete');

        $this->assertSame(Response::HTTP_FORBIDDEN, $this->client->getResponse()->getStatusCode());
    }

}
