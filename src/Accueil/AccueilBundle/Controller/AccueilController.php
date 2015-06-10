<?php

namespace Accueil\AccueilBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AccueilController extends Controller
{
    public function indexAction()
    {
        return $this->render('AccueilAccueilBundle::index.html.twig');
    }
}
