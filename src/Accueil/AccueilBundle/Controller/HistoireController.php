<?php

namespace Accueil\AccueilBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class HistoireController extends Controller
{
  public function histoireAction()
  {
  	$listeHistoire=$this->getDoctrine()
  					->getRepository('BDDBddClientBundle:Histoire')->findAll();
    return $this->render('AccueilAccueilBundle::histoire.html.twig',array('listeHistoire'=>$listeHistoire));
  }
}
