<?php

namespace Boutique\GalerieBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use BDD\BddClientBundle\Entity\Recette;
use BDD\BddClientBundle\Entity\Ingredient;

class GalerieController extends Controller
{
    public function indexAction()
    {
        return $this->render('BoutiqueGalerieBundle::categorie.html.twig');
    }

    public function panierAction()
    {
        return $this->render('BoutiqueGalerieBundle::panier.html.twig');
    }

    public function panierRecetteAction($idRecette) {
      $recette = $this->getDoctrine()
                    ->getRepository('BDDBddClientBundle:Recette')
                    ->find($idRecette);
      $ingredients = $this->getDoctrine()
                    ->getRepository('BDDBddClientBundle:Ingredient')
                    ->findByRecette($recette);
      return $this->render('BoutiqueGalerieBundle::articlesRecette.html.twig',
        array('ingredients' => $ingredients));
    }
}
