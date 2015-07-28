<?php

namespace Administrator\Administration\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use BDD\BddClientBundle\Entity\Commande;
use BDD\BddClientBundle\Entity\Article;
use BDD\BddClientBundle\Entity\Marches;

class CommandeController extends Controller
{
  public function indexAction(){
    $session = $this->getRequest()->getSession();
    if ($session->get('pren') != NULL) {
      if (strtolower($session->get('typ')) == 'administrateur') {
        $commandes = $this->getDoctrine()
                      ->getRepository('BDDBddClientBundle:Commande')
                      ->findAll();
        $statut = array();
        foreach($commandes as $c){
          if (!in_array($c->getStatus(), $statut)){
            $statut[] = $c->getStatus();
          }
        }
        sort($statut);
        $marches = $this->getDoctrine()
                      ->getRepository('BDDBddClientBundle:Marches')
                      ->findAll();
        return $this->
          render('AdministratorAdministrationAdminBundle:Commande:commandeCherche.html.twig',
          array('statut' => $statut, 'marche' => $marches));
      }
    }
    return $this->redirect($this->generateUrl('accueil_accueil_homepage'));
  }

  public function resultatAction(){
    $session = $this->getRequest()->getSession();
    if ($session->get('pren') != NULL) {
      if (strtolower($session->get('typ')) == 'administrateur') {
        $allCommandes = array();
        $statut = false;
        $enPrep = false;
        if (isset($_POST['statut']) && $_POST['statut'] != null){
          $allCommandes =  $this->getDoctrine()
                        ->getRepository('BDDBddClientBundle:Commande')
                        ->findByStatus($_POST['statut']);
          if (strstr($_POST['statut'], "cours")){
            $statut = true;
          }
          if (strstr($_POST['statut'], "preparation")){
            $enPrep = true;
          }
        }
        $marche = $_POST['marche'];
        $date1 = $_POST['date1']; $date2 = $_POST['date2'];
        $montant1 = $_POST['montant1']; $montant2 = $_POST['montant2'];

        $commandesM = array();
        if ($marche != "all"){
          foreach($allCommandes as $c){
            if ($c->getRetirerMarches()->getId() == $marche){
              $commandesM[] = $c;
            }
          }
        }else{
          $commandesM = $allCommandes;
        }
        $commandesD = array();

        if(($date1 == "" && $date2 == "")||($date1 == null && $date2 == null)){
          $commandesD = $commandesM;
        }else{
          if ($date1 == "" ||$date1 == null){
            $date1 = new \DateTime("2000-01-01");
          }else{
            $tmp = substr($date1, -4).'-'.substr($date1, 3, 2).'-'.substr($date1, 0, 2);
            $date1 = new \DateTime($tmp);
          }

          if ($date2 == "" || $date2 == null){
            $date2 = new \DateTime();
          }else{
            $tmp = substr($date2, -4).'-'.substr($date2, 3, 2).'-'.substr($date2, 0, 2);
            $date2 = new \DateTime($tmp);
          }

          foreach($commandesM as $c){
            if ($c->getDateCommande() >= $date1 && $c->getDateCommande() <= $date2 ){
              $commandesD[] = $c;
            }
          }
        }

        $commandesMo = array();

        if(($montant1 == "" && $montant2 == "")||($montant1 == null && $montant2 == null)){
          $commandesMo = $commandesD;
        }else{
          if ($montant1 == "" || $montant1 == null){
            $montant1 = 0;
          }

          if ($montant2 == "" || $montant2 == null){
            $montant2 = 1000000;
          }

          foreach($commandesD as $c){
            if ($c->getTotal() >= $montant1 && $c->getTotal() <= $montant2 ){
              $commandesMo[] = $c;
            }
          }
        }

        $panier = array();
        foreach($commandesMo as $p){
          $articles = array();
          $panier2 = explode(';', $p->getPanier());
          for($i=0; $i < count($panier2)-1; $i++){
            $art = explode(':', $panier2[$i]);
            $a = $this->getDoctrine()
                      ->getRepository('BDDBddClientBundle:Article')
                      ->find($art[0]);
            $articles[] = [$a, $art[1]];
          }
          $panier[] = [$articles, $p->getId()];
        }

        return $this->render('AdministratorAdministrationAdminBundle:Commande:resultat.html.twig', array('panier' => $panier, 'commandes' =>$commandesMo, 'encours' => $statut, 'enPrep' => $enPrep));
      }
    }
    return $this->redirect($this->generateUrl('accueil_accueil_homepage'));
  }

  public function optionAction(){
    $session = $this->getRequest()->getSession();
    if ($session->get('pren') != NULL) {
      if (strtolower($session->get('typ')) == 'administrateur') {

        if (isset($_POST['preparer'])){
          $allCommandes =  $this->getDoctrine()
                        ->getRepository('BDDBddClientBundle:Commande')
                        ->findByStatus("En cours");
          $commandes = array();
          $ligne = "";
          foreach($allCommandes as $c){
            if(isset($_POST[$c->getId()]) && $_POST[$c->getId()] != null){
              $commandes[] = $c;
              $ligne .= $c->getId().";";
            }
          }
          $comFinal = array();
          foreach($commandes as $c){
            $panier = array();
            $pan = explode(';', $c->getPanier());
            $categorie= array();
            for($a=0; $a < count($pan) -1; $a++){
              $article = explode(':', $pan[$a]);
              $art =  $this->getDoctrine()
                            ->getRepository('BDDBddClientBundle:Article')
                            ->find($article[0]);
              if (!in_array($art->getCategorie()->getId(), $categorie)){
                $categorie[] = $art->getCategorie()->getId();
              }
              $panier[] = [$art, $article[1]];
            }
            // On trie le panier par catégorie

            $panierbis = array();

            foreach($categorie as $cat){
              foreach($panier as $p){
                if ($cat ==  $p[0]->getCategorie()->getId()){
                  $panierbis[] = $p;
                }
              }
            }

            $comFinal[] = [$c, $panierbis];
          }

          // commandes => [commande, panier]
          // panier => [article, quantite]
          // On visualise, on imprime et on passe en "En preparation"

          return $this->render('AdministratorAdministrationAdminBundle:Commande:option.html.twig', array('commandes' => $comFinal, 'ligne' => $ligne));

        }else if(isset($_POST['valider'])){
          $allCommandes =  $this->getDoctrine()
                        ->getRepository('BDDBddClientBundle:Commande')
                        ->findByStatus("En preparation");
          $commandes = array();
          $ligne = "";
          foreach($allCommandes as $c){
            if(isset($_POST[$c->getId()]) && $_POST[$c->getId()] != null){
              $commandes[] = $c;
              $ligne .= $c->getId().";";
            }
          }
          foreach($commandes as $c){
            $c->setStatus("Terminé");
          }
          $em = $this->getDoctrine()->getManager();
          $em->flush();
        }
        return $this->redirect($this->generateUrl('administrator_commande_cherche'));
      }
    }
    return $this->redirect($this->generateUrl('accueil_accueil_homepage'));
  }

  public function supprimerAction($id){
    $session = $this->getRequest()->getSession();
    if ($session->get('pren') != NULL) {
      if (strtolower($session->get('typ')) == 'administrateur') {
        $commande = $this->getDoctrine()
          ->getRepository('BDDBddClientBundle:Commande')->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($commande);
        $em->flush();
        return $this->redirect($this->generateUrl('administrator_commande_cherche'));
      }
    }
    return $this->redirect($this->generateUrl('accueil_accueil_homepage'));
  }

  public function imprimerAction($commandes){
    $session = $this->getRequest()->getSession();
    if ($session->get('pren') != NULL) {
      if (strtolower($session->get('typ')) == 'administrateur') {
        $com = explode(';', $commandes);
        for($i=0; $i < count($com) -1; $i++){
          $co = $this->getDoctrine()
            ->getRepository('BDDBddClientBundle:Commande')->find($com[$i]);
          $co->setStatus("En preparation");
        }
        $em = $this->getDoctrine()->getManager();
        $em->flush();
        return $this->redirect($this->generateUrl('administrator_commande_cherche'));
      }
    }
    return $this->redirect($this->generateUrl('accueil_accueil_homepage'));
  }

}
