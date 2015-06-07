<?php

namespace BookManagerApp\ManagerBundle\Tests\Controller;

use BookManagerApp\ManagerBundle\Entity\Book;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;


class BookCrudTest extends KernelTestCase
{
    public function setUp()
    {
        self::bootKernel();
        $this->em = static::$kernel->getContainer()
            ->get('doctrine')
            ->getManager()
        ;

    }

    public function testAllBookCount()
    {
        $books = $this->em->getRepository('BookManagerAppManagerBundle:Book')->findAll();
        $this->assertCount(1, $books);
    }

    public function testCreateBook()
    {
        try {
            $this->em->getConnection()->beginTransaction();
            $book = new Book();
            $book->setTitle("Python CookBook");
            $book->setDescription("Python for experts");
            $book->setPages(330);
            $this->em->persist($book);
            $this->em->flush();
            $this->assertGreaterThan(1,$book->getId());
            $this->assertEquals("Python CookBook",$book->getTitle());

        } catch(Exception $e) {
            $this->em->getConnection()->rollback();
        } finally {
            $this->em->getConnection()->rollback();
        }
    }

    public function testUpdateBook()
    {

        try {
            $this->em->getConnection()->beginTransaction();
            $book = $this->em->getRepository('BookManagerAppManagerBundle:Book')->find(1);
            $this->assertEquals("Java CookBook", $book->getTitle());
            $book->setTitle("PHP CookBook");
            $this->em->flush();
            $updatedBook = $this->em->getRepository('BookManagerAppManagerBundle:Book')->find(1);
            $this->assertEquals("PHP CookBook",$updatedBook->getTitle());

        } catch (Exception $e) {
            $this->em->getConnection()->rollback();
        } finally {
            $this->em->getConnection()->rollback();
        }
    }

    public function testDeleteBook()
    {

        try {
            $this->em->getConnection()->beginTransaction();
            $book = $this->em->getRepository('BookManagerAppManagerBundle:Book')->find(1);
            $this->assertEquals(1, $book->getId());
            $this->em->remove($book);
            $this->em->flush();
            $emptyBookObject = $this->em->getRepository('BookManagerAppManagerBundle:Book')->find(1);
            $this->assertNull($emptyBookObject);

        } catch (Exception $e) {
            $this->em->getConnection()->rollback();
        } finally {
            $this->em->getConnection()->rollback();
        }
    }

    protected function tearDown()
    {
        parent::tearDown();
        $this->em->close();
    }

} 