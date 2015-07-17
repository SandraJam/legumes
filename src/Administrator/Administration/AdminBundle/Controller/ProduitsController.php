<?php

namespace Administrator\Administration\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use BDD\BddClientBundle\Entity\Categorie;
use BDD\BddClientBundle\Entity\Article;

class ProduitsController extends Controller
{
  public function indexAction(){
    $session = $this->getRequest()->getSession();
    if ($session->get('pren') != NULL) {
      /*  if (strtolower($session->get('typ')) == 'administrateur') {*/
          $categories = $this->getDoctrine()
                        ->getRepository('BDDBddClientBundle:Categorie')
                        ->findAll();
          return $this->render(
            'AdministratorAdministrationAdminBundle:Produits:produits.html.twig',
            array('categories' => $categories));
      /*  }else{
          return $this->redirect($this->generateUrl('accueil_accueil_homepage'));
        }*/
    }else{
      return $this->redirect($this->generateUrl('accueil_accueil_homepage'));
    }
  }

  public function ajoutCatAction(Request $request){
    $session = $this->getRequest()->getSession();
    if ($session->get('pren') != NULL) {
      /*  if (strtolower($session->get('typ')) == 'administrateur') {*/

      $categorie = new Categorie();
      $form = $this->createFormBuilder($categorie)
         ->add('nom','text')
         ->add('saison','text')
         ->add('suivant','submit')
         ->add('annuler','reset')
         ->getForm();
      $form->handleRequest($request);
      if($form->isValid()) {
        $categorie = $form -> getData();
        $message = "";
        if ($categorie->getNom() == "" || strpos(htmlentities($categorie->getNom(), ENT_QUOTES), '&gt;') || strpos(htmlentities($categorie->getNom(), ENT_QUOTES), '&lt;')){
          $message .= "Nom incorrect. ";
        }
        if ($categorie->getSaison() == "" || strpos(htmlentities($categorie->getSaison(), ENT_QUOTES), '&gt;') || strpos(htmlentities($categorie->getSaison(), ENT_QUOTES), '&lt;')){
          $message .= "Saison incorrecte. ";
        }

        if ($message == ""){
          $em = $this->getDoctrine()->getManager();
          $em->persist($categorie);
          $em->flush();
          return $this->redirect($this->generateUrl('administrator_administration_admin_gererProduits'));
        }else{
          $request->getSession()->getFlashBag()->add('notice', $message);
        }
      }
      return $this->render('AdministratorAdministrationAdminBundle:Produits:produitsAjout.html.twig',
            array('form' => $form->createView()) );

      /*  }else{
          return $this->redirect($this->generateUrl('accueil_accueil_homepage'));
        }*/
    }else{
      return $this->redirect($this->generateUrl('accueil_accueil_homepage'));
    }
  }

  public function supprimerCategorieAction($id){
    $session = $this->getRequest()->getSession();
    if ($session->get('pren') != NULL) {
      /*  if (strtolower($session->get('typ')) == 'administrateur') {*/
        $cat = $this->getDoctrine()
          ->getRepository('BDDBddClientBundle:Categorie')->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($cat);
        $em->flush();
        return $this->redirect($this->generateUrl('administrator_administration_admin_gererProduits'));
      /*  }else{
          return $this->redirect($this->generateUrl('accueil_accueil_homepage'));
        }*/
    }else{
      return $this->redirect($this->generateUrl('accueil_accueil_homepage'));
    }
  }

  public function modifierCategorieAction($id){
    $session = $this->getRequest()->getSession();
    if ($session->get('pren') != NULL) {
      /*  if (strtolower($session->get('typ')) == 'administrateur') {*/
      $categorie = $this->getDoctrine()
        ->getRepository('BDDBddClientBundle:Categorie')->find($id);
      return $this->render('AdministratorAdministrationAdminBundle:Produits:produitsCatModifier.html.twig',
              array('categorie' => $categorie) );
      /*  }else{
          return $this->redirect($this->generateUrl('accueil_accueil_homepage'));
        }*/
    }else{
      return $this->redirect($this->generateUrl('accueil_accueil_homepage'));
    }
  }

  public function modifierbisCategorieAction($id){
    $session = $this->getRequest()->getSession();
    if ($session->get('pren') != NULL) {
      /*  if (strtolower($session->get('typ')) == 'administrateur') {*/
      $categorie = $this->getDoctrine()
        ->getRepository('BDDBddClientBundle:Categorie')->find($id);
      $nom = $_POST['nom'];
      $saison = $_POST['saison'];
      $message="";
      if ($nom == "" || strpos(htmlentities($nom, ENT_QUOTES), '&gt;') || strpos(htmlentities($nom, ENT_QUOTES), '&lt;')){
        $message .= "Nom incorrect. ";
      }
      if ($saison == "" || strpos(htmlentities($saison, ENT_QUOTES), '&gt;') || strpos(htmlentities($saison, ENT_QUOTES), '&lt;')){
        $message .= "Saison incorrecte. ";
      }
      if($message ==""){
        $categorie->setNom($nom);
        $categorie->setSaison($saison);
        $em = $this->getDoctrine()->getManager();
        $em->flush();
      }

      return $this->redirect($this->generateUrl('administrator_administration_admin_gererProduits'));
      /*  }else{
          return $this->redirect($this->generateUrl('accueil_accueil_homepage'));
        }*/
    }else{
      return $this->redirect($this->generateUrl('accueil_accueil_homepage'));
    }
  }
}
