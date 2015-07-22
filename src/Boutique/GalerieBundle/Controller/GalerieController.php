<?php

namespace Boutique\GalerieBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use BDD\BddClientBundle\Entity\Recette;
use BDD\BddClientBundle\Entity\Ingredient;
use BDD\BddClientBundle\Entity\Article;
use BDD\BddClientBundle\Entity\Categorie;
use BDD\BddClientBundle\Entity\Marches;

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
        $session = $this->getRequest()->getSession();
        $panier = $session->get('panier');
        $articles = array();
        foreach ($panier as $artp){
          $art = $this->getDoctrine()
                        ->getRepository('BDDBddClientBundle:Article')
                        ->find($artp[0]);
          $articles[] = [$art, $artp[1]];
        }
        return $this->render('BoutiqueGalerieBundle::panier.html.twig', array('articles' => $articles));
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

    public function ajoutPanierAction(){
      $id = $_POST['id'];
      $quantite = $_POST['quantite'];
      $session = $this->getRequest()->getSession();
      if ($session->get('panier') == NULL){
        $session->set('panier', array());
        $session->set('nbPanier', 0);
      }
      $ok = false;
      $panier = $session->get('panier');
      for($i=0; $i < count($panier); $i++){
        if ($panier[$i][0] == $id){
          $panier[$i][1] += $quantite;
          $ok = true;
        }
      }
      if (!$ok){
        $panier[] = [$id, $quantite];
      }
      $session->set('panier', $panier);
      $nb = $session->get('nbPanier');
      $session->set('nbPanier', $nb+$quantite);
      return $this->redirect($this->generateUrl('boutique_galerie_homepage'));
    }

    public function supPanierAction($id){
      $session = $this->getRequest()->getSession();
      if ($session->get('panier') != NULL){
        $panier = $session->get('panier');
        $new = array();
        foreach($panier as $p){
          if ($p[0] != $id){
            $new[] = $p;
          }else{
            $quantite = $p[1];
          }
        }
        $session->set('panier', $new);
        $nb = $session->get('nbPanier');
        $session->set('nbPanier', $nb-$quantite);
      }
      return $this->redirect($this->generateUrl('boutique_galerie_panier'));
    }

    public function actuPanierAction(){
      $id = $_POST['id'];
      $quantite = $_POST['quantite'];
      $session = $this->getRequest()->getSession();
      if ($session->get('panier') == NULL){
        $session->set('panier', array());
        $session->set('nbPanier', 0);
      }

      $panier = $session->get('panier');
      $new = array();
      foreach($panier as $p){
        if ($p[0] != $id){
          $new[] = $p;
        }else{
          $quantite2 = $p[1];
        }
      }

      $new[] = [$id, $quantite];
      $session->set('panier', $new);
      $nb = $session->get('nbPanier');
      $session->set('nbPanier', $nb-$quantite2+$quantite);
      return $this->redirect($this->generateUrl('boutique_galerie_panier'));

    }

    public function marcheAction($panier) {
      $marches = $this->getDoctrine()
                    ->getRepository('BDDBddClientBundle:Marches')
                    ->findAll();
      return $this->render('BoutiqueGalerieBundle::validation.html.twig',
        array('panier' => $panier, 'marches' => $marches));
    }

    public function validemarcheAction() {
      
    }
}
