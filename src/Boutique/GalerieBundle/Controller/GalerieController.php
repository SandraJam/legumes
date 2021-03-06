<?php

namespace Boutique\GalerieBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use BDD\BddClientBundle\Entity\Recette;
use BDD\BddClientBundle\Entity\Ingredient;
use BDD\BddClientBundle\Entity\Article;
use BDD\BddClientBundle\Entity\Categorie;
use BDD\BddClientBundle\Entity\Marches;
use BDD\BddClientBundle\Entity\Utilisateurs;
use BDD\BddClientBundle\Entity\Commande;

class GalerieController extends Controller
{
    // Affiche la boutique
    public function indexAction()
    {
        $cat = $this->getDoctrine()
                      ->getRepository('BDDBddClientBundle:Categorie')
                      ->findAll();

        $categories = [];
        foreach($cat as $c){
          $a = $this->getDoctrine()
                      ->getRepository('BDDBddClientBundle:Article')
                      ->findByCategorie($c);
          $val = 0;
          foreach($a as $aa){
            $val = $val + $aa->getQtiteStock();
          }
          if ($a != null && $val != 0){
            $categories[] = [$c, $a[0]->getPhotos()];
          }
        }

        $articles = $this->getDoctrine()
                      ->getRepository('BDDBddClientBundle:Article')
                      ->findAll();

        return $this->render('BoutiqueGalerieBundle::categorie.html.twig',
          array('categories' => $categories, 'articles' => $articles));
    }

    // Affiche le panier
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

    // Affiche la boutique relative a une recette
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

    // Ajouter un article au panier
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
      return $this->render('BoutiqueGalerieBundle::choixChemin.html.twig');
    }

    // Supprimer un article du panier
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

    // Modifier un article du panier
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

    // Choisir son marché de récupération
    public function marcheAction() {
      $session = $this->getRequest()->getSession();
      if ($session->get('users') != NULL) {
        $panier = $_POST['panier'];
        $remarque = $_POST['remarque'];
        $marches = $this->getDoctrine()
                      ->getRepository('BDDBddClientBundle:Marches')
                      ->findAll();
        return $this->render('BoutiqueGalerieBundle::validation.html.twig',
          array('panier' => $panier, 'marches' => $marches, 'total' => $_POST['total'], 'remarque' => $remarque));
      }
      return $this->redirect($this->generateUrl('accueil_accueil_homepage'));
    }

    // Enregistre la commande + envoie le mail de validation
    public function validemarcheAction() {
      $session = $this->getRequest()->getSession();
      if ($session->get('users') != NULL) {
        $panier = $_POST['panier'];
        $total = $_POST['total'];
        $marche = $this->getDoctrine()
                      ->getRepository('BDDBddClientBundle:Marches')
                      ->find($_POST['marche']);
        $remarque = $_POST['remarque'];
        $user = $this->getDoctrine()
                      ->getRepository('BDDBddClientBundle:Utilisateurs')
                      ->find($session->get('id'));

        $commande = new Commande();
        $commande->setJourCommande($_POST['jour'.$marche->getId()]);
        $commande->setNumCommande(time().$commande->getId());
        $commande->setStatus("En cours");
        $commande->setDateCommande(new \DateTime());
        $commande->setUtilisateurs($user);
        $commande->setRetirerMarches($marche);
        $commande->setPanier($panier);
        $commande->setRemarque($remarque);
        $commande->setTotal($total);
        $em = $this->getDoctrine()->getManager();
        $em->persist($commande);

        $articles = array();
        $panier2 = explode(';', $panier);
        for($i=0; $i < count($panier2)-1; $i++){
          $art = explode(':', $panier2[$i]);
          $a = $this->getDoctrine()
                        ->getRepository('BDDBddClientBundle:Article')
                        ->find($art[0]);
          $val = $a->getQtiteStock();
          $a->setQtiteStock($val - $art[1]);
          $em->flush();
          $articles[] = [$a, $art[1]];
        }
        $em->flush();

        $message = \Swift_Message::newInstance();
        $body = '<html><body>
          <img src="'.$message->embed(\Swift_Image::fromPath('bundles/accueilaccueil/images/email.png')) .'"><br/>
          <strong> Les Légumes du Val de Loire vous remercie de votre commande. </strong>
          <p> Commande N° '.$commande->getNumCommande().' à récupérer le '.$commande->getJourCommande().' prochain au marché de '.$marche->getLieu().'
            <br/>
            <br/> Récupitulatif pour un total de '.$total.'€: </p>

          <table style="width: 90%; border: 1px solid grey; border-collapse: collapse;">
            <tr>
              <td>Nom</td><td>Quantité</td><td>Prix</td>
            </tr>';

          foreach($articles as $art){
            $body .= '<tr><td>'.$art[0]->getNom().'</td><td>'.$art[1].'</td><td>'.($art[1]*$art[0]->getPrix()).'€</td></tr>';
          }

          $body .= '</table></body></html>';
          $body .= "Attention! Certains produits peuvent être indisponible le jour de votre retrait! Le calcul du tarif sera donc revu.";


        $dir = $this->getRequest()->server->get('DOCUMENT_ROOT') . $this->getRequest()->getBasePath() . "/images/";
        $message->setSubject('Votre commande Les Légumes du Val de Loire N°'.$commande->getNumCommande())
          ->setFrom($this->container->getParameter('accueil_accueil.emails.contact_email'))
          ->setTo($user->getEmail())
          ->setBody($body, 'text/html');

        $this->get('mailer')->send($message);
        $session->set('panier', array());
        $session->set('nbPanier', 0);

        return $this->render('BoutiqueGalerieBundle::okValidation.html.twig',
          array('numCommande' => $commande->getNumCommande(), 'marche' => $marche));
      }
      return $this->redirect($this->generateUrl('accueil_accueil_homepage'));
    }
}
