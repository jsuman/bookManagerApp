<?php

namespace BookManagerApp\ManagerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use BookManagerApp\ManagerBundle\Entity\Book;
use BookManagerApp\ManagerBundle\Form\BookType;

class BookController extends Controller
{
    public function indexAction()
    {
        $entityManager = $this->getDoctrine()->getManager();
        $books = $entityManager->getRepository('BookManagerAppManagerBundle:Book')->findAll();
        return $this->render('BookManagerAppManagerBundle:Book:index.html.twig', array(
            'books'=>$books
        ));
    }

    public function showAction($id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $book = $entityManager->getRepository('BookManagerAppManagerBundle:Book')->find($id);
        $deleteForm = $this->createFormBuilder()
            ->setAction($this->generateUrl('book_delete',array('id'=>$id)))
            ->setMethod('DELETE')
            ->add('submit','submit', array('label'=>'Delete Book'))
            ->getForm();

        return $this->render('BookManagerAppManagerBundle:Book:show.html.twig', array(
            'book'=> $book,
            'delete_form'=>$deleteForm->createView()
        ));
    }

    public function newAction()
    {
        $form = $this->createForm(new BookType(), new Book(), array(
            'action'=> $this->generateUrl('book_create'),
            'method'=> 'POST'
        ));
        $form->add('submit', 'submit', array('label'=>'Create Book'));
        return $this->render('BookManagerAppManagerBundle:Book:new.html.twig', array(
            'form'=> $form->createView()
        ));
    }
    public function createAction(Request $request)
    {
        $book = new Book();
        $form = $this->createForm(new BookType(), $book, array(
            'action'=> $this->generateUrl('book_create'),
            'method'=> 'POST'
        ));
        $form->add('submit', 'submit', array('label'=>'Create Book'));
        $form->handleRequest($request);

        if($form->isValid())
        {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($book);
            $entityManager->flush();
            $this->get('session')->getFlashBag()->add('msg','Your book has been created');
            return $this->redirect($this->generateUrl('book_show', array('id'=>$book->getId())));
        }

        $this->get('session')->getFlashBag()->add('msg','Validation error occured');
        return $this->render('BookManagerAppManagerBundle:Book:new.html.twig', array(
            'form'=> $form->createView()
        ));

    }
    public function editAction($id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $book = $entityManager->getRepository('BookManagerAppManagerBundle:Book')->find($id);
        $form = $this->createForm(new BookType(), $book, array(
            'action'=>$this->generateUrl('book_update', array('id'=>$book->getId())),
            'method'=>'PUT'
        ));
        $form->add('submit','submit', array('label'=>'Update Book'));
        return $this->render('BookManagerAppManagerBundle:Book:edit.html.twig', array(
            'form'=>$form->createView()
        ));
    }

    public function updateAction(Request $request, $id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $book = $entityManager->getRepository('BookManagerAppManagerBundle:Book')->find($id);
        $form = $this->createForm(new BookType(), $book, array(
            'action'=>$this->generateUrl('book_update', array('id'=>$book->getId())),
            'method'=>'PUT'
        ));
        $form->add('submit','submit', array('label'=>'Update Book'));
        $form->handleRequest($request);

        if($form->isValid())
        {
            $entityManager->flush();
            $this->get('session')->getFlashBag()->add('msg','Your book has been updated');
            return $this->redirect($this->generateUrl('book_show', array('id'=>$id)));
        }
        return $this->render('BookManagerAppManagerBundle:Book:edit.html.twig', array(
            'form'=>$form->createView()
        ));

    }

    public function deleteAction(Request $request, $id)
    {
        $deleteForm = $this->createFormBuilder()
            ->setAction($this->generateUrl('book_delete',array('id'=>$id)))
            ->setMethod('DELETE')
            ->add('submit','submit', array('label'=>'Delete Book'))
            ->getForm();
        $deleteForm->handleRequest($request);

        if($deleteForm->isValid())
        {
            $entityManager = $this->getDoctrine()->getManager();
            $book = $entityManager->getRepository('BookManagerAppManagerBundle:Book')->find($id);
            $entityManager->remove($book);
            $entityManager->flush();
        }
        $this->get('session')->getFlashBag()->add('msg','Book has been deleted');
        return $this->redirect($this->generateUrl('book'));
    }

} 