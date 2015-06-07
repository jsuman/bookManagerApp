<?php

namespace BookManagerApp\ManagerBundle\Tests\Controller;

use BookManagerApp\ManagerBundle\Entity\Book;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use Doctrine\Common\DataFixtures\Purger;


class BookCrudTest extends WebTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;


    public function setUp()
    {
        self::bootKernel();
        $this->em = static::$kernel->getContainer()
            ->get('doctrine')
            ->getManager()
        ;
        $this->loadFixtures(array(
            "BookManagerApp\ManagerBundle\DataFixtures\ORM\LoadBookData"
        ), null, 'doctrine',Purger\ORMPurger::PURGE_MODE_TRUNCATE);
    }

    public function testAllBookCount()
    {
        $books = $this->em->getRepository('BookManagerAppManagerBundle:Book')->findAll();
        $this->assertCount(1, $books);
    }

    public function testCreateBook()
    {
            $book = new Book();
            $book->setTitle("Python CookBook");
            $book->setDescription("Python for experts");
            $book->setPages(330);
            $this->em->persist($book);
            $this->em->flush();
            $this->assertGreaterThan(1,$book->getId());
            $this->assertEquals("Python CookBook",$book->getTitle());
    }

    public function testUpdateBook()
    {

            $book = $this->em->getRepository('BookManagerAppManagerBundle:Book')->find(1);
            $this->assertEquals("Java CookBook", $book->getTitle());
            $book->setTitle("PHP CookBook");
            $this->em->flush();
            $updatedBook = $this->em->getRepository('BookManagerAppManagerBundle:Book')->find(1);
            $this->assertEquals("PHP CookBook",$updatedBook->getTitle());
    }

    public function testDeleteBook()
    {
            $book = $this->em->getRepository('BookManagerAppManagerBundle:Book')->find(1);
            $this->assertEquals(1, $book->getId());
            $this->em->remove($book);
            $this->em->flush();
            $emptyBookObject = $this->em->getRepository('BookManagerAppManagerBundle:Book')->find(1);
            $this->assertNull($emptyBookObject);
    }

    protected function tearDown()
    {
        parent::tearDown();
        $this->em->close();
    }

} 