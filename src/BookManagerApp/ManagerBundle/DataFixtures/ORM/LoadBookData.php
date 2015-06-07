<?php
namespace BookManagerApp\ManagerBundle\DataFixtures\ORM;

use BookManagerApp\ManagerBundle\Entity\Book;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadBookData implements FixtureInterface {

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $book = new Book();
        $book->setTitle("Java CookBook");
        $book->setDescription("Java CookBook for Experts");
        $book->setPages(330);
        $manager->persist($book);
        $manager->flush();
    }
}