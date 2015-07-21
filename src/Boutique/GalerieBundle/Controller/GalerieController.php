<?php

namespace Boutique\GalerieBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use BDD\BddClientBundle\Entity\Recette;
use BDD\BddClientBundle\Entity\Ingredient;
use BDD\BddClientBundle\Entity\Article;
use BDD\BddClientBundle\Entity\Categorie;

class GalerieController extends Controller
{
    public function indexAction()
    {
        $bonplans = $this->getDoctrine()
                      ->getRepository('BDDBddClientBundle:Article')
                      ->findByBonplan(true);
        $cat = $this->getDoctrine()
                      ->getRepository('BDDBddClientBundle:Categorie')
                      ->findAll();

        $categories = [];
        foreach($cat as $c){
          $a = $this->getDoctrine()
                        ->getRepository('BDDBddClientBundle:Article')
                        ->findOneByCategorie($c);
          $categories[] = [$c, $a->getPhotos()];
        }

        $articles = $this->getDoctrine()
                      ->getRepository('BDDBddClientBundle:Article')
                      ->findAll();

        return $this->render('BoutiqueGalerieBundle::categorie.html.twig',
          array('bonplans' => $bonplans, 'categories' => $categories, 'articles' => $articles));
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
