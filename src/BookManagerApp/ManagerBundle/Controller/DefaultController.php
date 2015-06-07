<?php

namespace BookManagerApp\ManagerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('BookManagerAppManagerBundle:Default:index.html.twig', array('name' => $name));
    }
}
