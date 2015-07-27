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
        if (isset($_POST['statut']) && $_POST['statut'] != null){
          $allCommandes =  $this->getDoctrine()
                        ->getRepository('BDDBddClientBundle:Commande')
                        ->findByStatus($_POST['statut']);
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

        return $this->render('AdministratorAdministrationAdminBundle:Commande:resultat.html.twig', array('panier' => $panier, 'commandes' =>$commandesMo));
      }
    }
    return $this->redirect($this->generateUrl('accueil_accueil_homepage'));
  }

  public function optionAction(){
    $session = $this->getRequest()->getSession();
    if ($session->get('pren') != NULL) {
      if (strtolower($session->get('typ')) == 'administrateur') {
        return $this->render('AdministratorAdministrationAdminBundle:Commande:option.html.twig');
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

}
