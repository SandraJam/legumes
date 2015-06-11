<?php

namespace BDD\BddClientBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('BDDBddClientBundle:Default:index.html.twig', array('name' => $name));
    }
}
