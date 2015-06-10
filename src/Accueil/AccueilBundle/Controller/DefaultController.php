<?php

namespace Accueil\AccueilBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('AccueilAccueilBundle:Default:index.html.twig', array('name' => $name));
    }
}
