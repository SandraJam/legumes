<?php

namespace NotreHistoire\HistoireBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('NotreHistoireHistoireBundle:Default:index.html.twig', array('name' => $name));
    }
}
