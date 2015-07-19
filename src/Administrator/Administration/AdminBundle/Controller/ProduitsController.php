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
        $articles = $this->getDoctrine()
          ->getRepository('BDDBddClientBundle:Article')->findByCategorie($cat);
        $em = $this->getDoctrine()->getManager();
        foreach($articles as $art){
          $em->remove($art);
        }
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


  public function ajoutArtAction($id, Request $request){
    $session = $this->getRequest()->getSession();
    if ($session->get('pren') != NULL) {
      /*  if (strtolower($session->get('typ')) == 'administrateur') {*/

      $article = new Article();
      $cat = $this->getDoctrine()
                    ->getRepository('BDDBddClientBundle:Categorie')
                    ->find($id);
      $form = $this->createFormBuilder($article)
         ->add('nom','text')
         ->add('descr','textarea')
         ->add('recommandation','text')
         ->add('qtiteVente','text')
         ->add('qtiteStock','number')
         ->add('commandeMax','number')
         ->add('prix','number')
         ->add('bonplan','choice', array(
             'choices' => array(true => 'Oui', false => 'Non'),
             'expanded' => true,
             'multiple' => false))
         ->add('photos','file')
         ->add('suivant','submit')
         ->add('annuler','reset')
         ->getForm();
      $form->handleRequest($request);
      if($form->isValid()) {
        $article = $form -> getData();
        $message = "";
        if ($article->getNom() == "" || strpos(htmlentities($article->getNom(), ENT_QUOTES), '&gt;') || strpos(htmlentities($article->getNom(), ENT_QUOTES), '&lt;')){
          $message .= "Nom incorrect. ";
        }
        if ($article->getDescr() == "" || strpos(htmlentities($article->getDescr(), ENT_QUOTES), '&gt;') || strpos(htmlentities($article->getDescr(), ENT_QUOTES), '&lt;')){
          $message .= "Description incorrecte. ";
        }
        if ($article->getRecommandation() == "" || strpos(htmlentities($article->getRecommandation(), ENT_QUOTES), '&gt;') || strpos(htmlentities($article->getRecommandation(), ENT_QUOTES), '&lt;')){
          $message .= "Recommandation incorrecte. ";
        }
        if ($article->getQtiteVente() == "" || strpos(htmlentities($article->getQtiteVente(), ENT_QUOTES), '&gt;') || strpos(htmlentities($article->getQtiteVente(), ENT_QUOTES), '&lt;')){
          $message .= "Quantite de Vente incorrecte. ";
        }
        if ($article->getQtiteStock() == "" || strpos(htmlentities($article->getQtiteStock(), ENT_QUOTES), '&gt;') || strpos(htmlentities($article->getQtiteStock(), ENT_QUOTES), '&lt;')){
          $message .= "Quantite de Stock incorrect. ";
        }
        if ($article->getCommandeMax() == "" || strpos(htmlentities($article->getCommandeMax(), ENT_QUOTES), '&gt;') || strpos(htmlentities($article->getCommandeMax(), ENT_QUOTES), '&lt;')){
          $message .= "Commande Max incorrect. ";
        }
        if ($article->getPrix() == "" || strpos(htmlentities($article->getPrix(), ENT_QUOTES), '&gt;') || strpos(htmlentities($article->getPrix(), ENT_QUOTES), '&lt;')){
          $message .= "Prix incorrect. ";
        }
        if(!$article->getPhotos()->getSize()){
          $message .= "Photos trop grande!";
        }

        if ($message == ""){
          $tableauCarIndesirable = array(" ", "-", "#", "~", "\t", "\n", "\r", "\0", "\x0B", "\xA0");
          $uploadedFile = $article -> getPhotos();
          $fileName = str_replace($tableauCarIndesirable, "", $uploadedFile -> getClientOriginalName());
          $dir = $this->getRequest()->server->get('DOCUMENT_ROOT') . $this->getRequest()->getBasePath() . "/images/";
          $uploadedFile->move($dir, $fileName.time());
          $article->setPhotos($fileName.time());

          $article->setCategorie($cat);
          $em = $this->getDoctrine()->getManager();
          $em->persist($article);
          $em->flush();
          return $this->redirect($this->generateUrl('administrator_base_article', array('id' => $cat->getId())));
        }else{
          $request->getSession()->getFlashBag()->add('notice', $message);
        }
      }
      return $this->render('AdministratorAdministrationAdminBundle:Produits:produitsProduitsAjout.html.twig',
            array('form' => $form->createView(), 'cat' => $cat) );

      /*  }else{
          return $this->redirect($this->generateUrl('accueil_accueil_homepage'));
        }*/
    }else{
      return $this->redirect($this->generateUrl('accueil_accueil_homepage'));
    }
  }

  public function modifierArtAction($id){
    $session = $this->getRequest()->getSession();
    if ($session->get('pren') != NULL) {
      /*  if (strtolower($session->get('typ')) == 'administrateur') {*/
        $article = $this->getDoctrine()
          ->getRepository('BDDBddClientBundle:Article')->find($id);
        $categories = $this->getDoctrine()
          ->getRepository('BDDBddClientBundle:Categorie')->findAll();
        return $this->render('AdministratorAdministrationAdminBundle:Produits:produitsProduitsModif.html.twig',
                array('article' => $article, 'categories' => $categories) );
     /* }else{
          return $this->redirect($this->generateUrl('accueil_accueil_homepage'));
        }*/
    }else{
      return $this->redirect($this->generateUrl('accueil_accueil_homepage'));
    }
  }

  public function modifierbisArtAction($id){
    $session = $this->getRequest()->getSession();
    if ($session->get('pren') != NULL) {
      /*  if (strtolower($session->get('typ')) == 'administrateur') {*/
      $article = $this->getDoctrine()
        ->getRepository('BDDBddClientBundle:Article')->find($id);

      $nom = $_POST['nom'];
      $recommandation = $_POST['recommandation'];
      $qtiteVente = $_POST['qtiteVente'];
      $qtiteStock = $_POST['qtiteStock'];
      $commandeMax = $_POST['commandeMax'];
      $prix = $_POST['prix'];
      $categorie = $_POST['categorie'];
      $descr = $_POST['descr'];
      $photos = $_FILES['photos'];
      $message="";

      if ($nom == "" || strpos(htmlentities($nom, ENT_QUOTES), '&gt;') || strpos(htmlentities($nom, ENT_QUOTES), '&lt;')){
        $message .= "Nom incorrect. ";
      }
      if ($descr == "" || strpos(htmlentities($descr, ENT_QUOTES), '&gt;') || strpos(htmlentities($descr, ENT_QUOTES), '&lt;')){
        $message .= "Description incorrecte. ";
      }
      if ($recommandation == "" || strpos(htmlentities($recommandation, ENT_QUOTES), '&gt;') || strpos(htmlentities($recommandation, ENT_QUOTES), '&lt;')){
        $message .= "Recommandation incorrecte. ";
      }
      if ($qtiteVente == "" || strpos(htmlentities($qtiteVente, ENT_QUOTES), '&gt;') || strpos(htmlentities($qtiteVente, ENT_QUOTES), '&lt;')){
        $message .= "Quantite de Vente incorrecte. ";
      }
      if ($qtiteStock == "" || strpos(htmlentities($qtiteStock, ENT_QUOTES), '&gt;') || strpos(htmlentities($qtiteStock, ENT_QUOTES), '&lt;')){
        $message .= "Quantite de Stock incorrect. ";
      }
      if ($commandeMax == "" || strpos(htmlentities($commandeMax, ENT_QUOTES), '&gt;') || strpos(htmlentities($commandeMax, ENT_QUOTES), '&lt;')){
        $message .= "Commande Max incorrect. ";
      }
      if ($prix == "" || strpos(htmlentities($prix, ENT_QUOTES), '&gt;') || strpos(htmlentities($prix, ENT_QUOTES), '&lt;')){
        $message .= "Prix incorrect. ";
      }
      if($photos['size'] == 0 && $photos['name'] != ""){
        $message .= "Photos trop grande!";
      }

      if ($message == ""){
        if($photos['name'] != ""){
          $tableauCarIndesirable = array(" ", "-", "#", "~", "\t", "\n", "\r", "\0", "\x0B", "\xA0");
          $fileName = str_replace($tableauCarIndesirable, "", $photos['name']);
          $dir = $this->getRequest()->server->get('DOCUMENT_ROOT') . $this->getRequest()->getBasePath() . "/images/";
          move_uploaded_file($photos["tmp_name"], $dir.$fileName.time());
          unlink($dir.$article->getPhotos());
          $article->setPhotos($fileName.time());
        }


        if (isset($_POST['bonplan'])){
          $article->setBonplan(true);
        }else{
          $article->setBonplan(false);
        }
        $article->setNom($nom);
        $article->setRecommandation($recommandation);
        $article->setQtiteVente($qtiteVente);
        $article->setQtiteStock($qtiteStock);
        $article->setCommandeMax($commandeMax);
        $article->setPrix($prix);
        $article->setDescr($descr);

        $cat = $this->getDoctrine()
          ->getRepository('BDDBddClientBundle:Categorie')->find($categorie);
        $article->setCategorie($cat);

        $em = $this->getDoctrine()->getManager();
        $em->flush();
      }

      return $this->redirect($this->generateUrl('administrator_base_article', array('id' => $article->getCategorie()->getId())));

     /* }else{
          return $this->redirect($this->generateUrl('accueil_accueil_homepage'));
        }*/
    }else{
      return $this->redirect($this->generateUrl('accueil_accueil_homepage'));
    }
  }

  public function supprimerArtAction($id){
    $session = $this->getRequest()->getSession();
    if ($session->get('pren') != NULL) {
      /*  if (strtolower($session->get('typ')) == 'administrateur') {*/
        $article = $this->getDoctrine()
          ->getRepository('BDDBddClientBundle:Article')->find($id);
        $em = $this->getDoctrine()->getManager();
        unlink($this->getRequest()->server->get('DOCUMENT_ROOT').
                $this->getRequest()->getBasePath() . "/images/".$article->getPhotos());
        $em->remove($article);
        $em->flush();
        return $this->redirect($this->generateUrl('administrator_base_article', array('id' => $article->getCategorie()->getId())));
      /*  }else{
          return $this->redirect($this->generateUrl('accueil_accueil_homepage'));
        }*/
    }else{
      return $this->redirect($this->generateUrl('accueil_accueil_homepage'));
    }
  }

  public function baseArtAction($id){
    $session = $this->getRequest()->getSession();
    if ($session->get('pren') != NULL) {
      /*  if (strtolower($session->get('typ')) == 'administrateur') {*/
          $categorie = $this->getDoctrine()
                        ->getRepository('BDDBddClientBundle:Categorie')
                        ->find($id);
          $articles = $this->getDoctrine()
                        ->getRepository('BDDBddClientBundle:Article')
                        ->findByCategorie($id);
          return $this->render(
            'AdministratorAdministrationAdminBundle:Produits:produitsProduits.html.twig',
            array('cat' => $categorie, 'articles' => $articles));
      /*  }else{
          return $this->redirect($this->generateUrl('accueil_accueil_homepage'));
        }*/
    }else{
      return $this->redirect($this->generateUrl('accueil_accueil_homepage'));
    }
  }

}
