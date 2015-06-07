<?php

namespace BookManagerApp\ManagerBundle\Tests\Controller;
use Doctrine\Common\DataFixtures\Purger;
use Liip\FunctionalTestBundle\Test\WebTestCase;

class BookControllerTest extends WebTestCase
{
    public function setUp()
    {
        $this->loadFixtures(array(
            "BookManagerApp\ManagerBundle\DataFixtures\ORM\LoadBookData"
        ), null, 'doctrine',Purger\ORMPurger::PURGE_MODE_TRUNCATE);

    }

    public function testCreateActionCreateNewBook()
    {
            $client = static::createClient();
            $crawler = $client->request("GET", "/");
            $crawler = $client->click($crawler->selectLink('Create New Book')->link());
            $form = $crawler->selectButton("bookManagerApp_managerbundle_book[submit]")->form(array(
                "bookManagerApp_managerbundle_book[title]" => "Java CookBook",
                "bookManagerApp_managerbundle_book[description]" => "Java cookbook for beginners ",
                "bookManagerApp_managerbundle_book[pages]" => 220
            ));
            $method = $form->getMethod();
            $this->assertEquals("POST", $method);
            $client->submit($form);
            $crawler = $client->followRedirect();
            $this->assertGreaterThan(0, $crawler->filter('h1:contains("Java CookBook")')->count(), 'Missing element h1:contains("Java CookBook")');

    }
    public function testIndexActionDisplayBook()
    {

            $client = static::createClient();
            $crawler = $client->request("GET", "/");
            $result = $crawler->filter('html:contains("Java CookBook")')->count();
            $this->assertGreaterThan(0, $result);

    }

    public function testEditActionEditBook()
    {
            $client = static::createClient();
            $crawler = $client->request("GET", "/show/1");
            $crawler = $client->click($crawler->selectLink("Edit Book")->link());
            $form = $crawler->selectButton("bookManagerApp_managerbundle_book[submit]")->form(array(
                "bookManagerApp_managerbundle_book[title]" => "PHP CookBook",
                "bookManagerApp_managerbundle_book[description]" => "For PHP beginners ",
                "bookManagerApp_managerbundle_book[pages]" => 110
            ));
            $client->submit($form);
            $crawler = $client->followRedirect();
            $this->assertGreaterThan(0, $crawler->filter('h1:contains("PHP CookBook")')->count(), 'Missing element h1:contains("PHP CookBook")');
    }

    public function testDeleteActionDeleteBook()
    {
            $client = static::createClient();
            $crawler = $client->request("GET", "/show/1");
            $client->submit($crawler->selectButton("Delete")->form());
            $crawler = $client->followRedirect();
            $this->assertNotRegExp('/PHP CookBook/', $client->getResponse()->getContent());
    }

} 