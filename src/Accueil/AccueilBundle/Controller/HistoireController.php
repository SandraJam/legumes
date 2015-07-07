<?php

namespace Accueil\AccueilBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class HistoireController extends Controller
{
  public function histoireAction()
  {
    return $this->render('AccueilAccueilBundle::histoire.html.twig');
  }
}
