<?php

namespace Administrator\Administration\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use BDD\BddClientBundle\Entity\Recette;
use BDD\BddClientBundle\Entity\Ingredient;
use BDD\BddClientBundle\Entity\Article;

class RecetteController extends Controller
{
  public function baseAction(){
    $session = $this->getRequest()->getSession();
    if ($session->get('pren') != NULL) {
      /*  if (strtolower($session->get('typ')) == 'administrateur') {*/
          $recettes = $this->getDoctrine()
                        ->getRepository('BDDBddClientBundle:Recette')
                        ->findAll();
          $ingredients = $this->getDoctrine()
                        ->getRepository('BDDBddClientBundle:Ingredient')
                        ->findAll();
          return $this->render('AdministratorAdministrationAdminBundle:Recette:recetteAdmin.html.twig', array(
            'recettes' => $recettes,
            'ingredients' => $ingredients
          ));
      /*  }else{
          return $this->redirect($this->generateUrl('accueil_accueil_homepage'));
        }*/
    }else{
      return $this->redirect($this->generateUrl('accueil_accueil_homepage'));
    }
  }

  public function ajoutAction(Request $request){
    $session = $this->getRequest()->getSession();
    if ($session->get('pren') != NULL) {
      /*  if (strtolower($session->get('typ')) == 'administrateur') {*/
      $recette = new Recette();
      $form = $this->createFormBuilder($recette)
         ->add('nom','text')
         ->add('tpsPrep','text')
         ->add('tpsCuisson','text')
         ->add('preparation','textarea')
         ->add('photo','file')
         ->add('suivant','submit')
         ->add('annuler','reset')
         ->getForm();
      $form->handleRequest($request);
      if($form->isValid()) {
        $recette = $form -> getData();
        $message = "";
        if ($recette->getNom() == "" || strpos(htmlentities($recette->getNom(), ENT_QUOTES), '&gt;') || strpos(htmlentities($recette->getNom(), ENT_QUOTES), '&lt;')){
          $message .= "Nom incorrect. ";
        }
        if ($recette->getTpsPrep() == "" || strpos(htmlentities($recette->getTpsPrep(), ENT_QUOTES), '&gt;') || strpos(htmlentities($recette->getTpsPrep(), ENT_QUOTES), '&lt;')){
          $message .= "Temps de Préparation incorrect. ";
        }
        if ($recette->getTpsCuisson() == "" || strpos(htmlentities($recette->getTpsCuisson(), ENT_QUOTES), '&gt;') || strpos(htmlentities($recette->getTpsCuisson(), ENT_QUOTES), '&lt;')){
          $message .= "Temps de Cuisson incorrect. ";
        }
        if ($recette->getPreparation() == "" || strpos(htmlentities($recette->getPreparation(), ENT_QUOTES), '&gt;') || strpos(htmlentities($recette->getPreparation(), ENT_QUOTES), '&lt;')){
          $message .= "Préparation incorrect. ";
        }
        if(strpos($recette->getPhoto()->getMimeType(), "image") === false){
          $message .= "Format image incorrect. ";
        }

        if ($message == ""){
          $uploadedFile = $recette -> getPhoto();
          $fileName = $uploadedFile -> getClientOriginalName();
          $dir = $this->getRequest()->server->get('DOCUMENT_ROOT') . $this->getRequest()->getBasePath() . "/images/";
          $uploadedFile->move($dir, $fileName.time());
          $recette->setPhoto($fileName.time());
          $recette->setVisible(FALSE);
          $em = $this->getDoctrine()->getManager();
          $em->persist($recette);
          $em->flush();
          return $this->redirect($this->generateUrl('administration_admin_ajoutIngredient', array('id' => $recette->getId())));
        }else{
          $request->getSession()->getFlashBag()->add('notice', $message);
        }
      }
      return $this->render('AdministratorAdministrationAdminBundle:Recette:recetteAjout.html.twig',
            array('form' => $form->createView()) );
      /*  }else{
          return $this->redirect($this->generateUrl('accueil_accueil_homepage'));
        }*/
    }else{
      return $this->redirect($this->generateUrl('accueil_accueil_homepage'));
    }
  }

  public function ajoutingredientAction($id, Request $request) {
    $session = $this->getRequest()->getSession();
    if ($session->get('pren') != NULL) {
      /*  if (strtolower($session->get('typ')) == 'administrateur') {*/
      $recette = $this->getDoctrine()
          ->getRepository('BDDBddClientBundle:Recette')->find($id);
      $ingredients = $this->getDoctrine()
          ->getRepository('BDDBddClientBundle:Ingredient')->findByRecette($recette);
      $articles = $this->getDoctrine()
          ->getRepository('BDDBddClientBundle:Article')->findAll();

      $ingredient = new Ingredient();
      $form = $this->createFormBuilder($ingredient)
         ->add('nom','text')
         ->add('quantite','text')
         ->add('article','choice', $articles)
         ->add('ajouter','submit')
         ->add('annuler','reset')
         ->getForm();
      $form->handleRequest($request);
      if($form->isValid()) {
        $ingredient = $form -> getData();
        $message = "";
        if ($ingredient->getNom() == "" || strpos(htmlentities($ingredient->getNom(), ENT_QUOTES), '&gt;') || strpos(htmlentities($recette->getNom(), ENT_QUOTES), '&lt;')){
          $message .= "Nom incorrect. ";
        }
        if ($ingredient->getQuantite() == "" || strpos(htmlentities($ingredient->getQuantite(), ENT_QUOTES), '&gt;') || strpos(htmlentities($recette->getQuantite(), ENT_QUOTES), '&lt;')){
          $message .= "Quantité incorrect. ";
        }

        if ($message == ""){
          $ingredient->setRecette($recette);
          $em = $this->getDoctrine()->getManager();
          $em->persist($ingredient);
          $em->flush();
          return $this->redirect($this->generateUrl('administration_admin_ajoutIngredient', array('id' => $recette->getId())));
        }else{
          $request->getSession()->getFlashBag()->add('notice', $message);
        }
      }
      return $this->render('AdministratorAdministrationAdminBundle:Recette:recetteIngredients.html.twig',
            array('form' => $form->createView(), 'ingredients' => $ingredients) );
      /*  }else{
          return $this->redirect($this->generateUrl('accueil_accueil_homepage'));
        }*/
    }else{
      return $this->redirect($this->generateUrl('accueil_accueil_homepage'));
    }
  }

  public function supprimerIngredientAction($id) {
    $session = $this->getRequest()->getSession();
    if ($session->get('pren') != NULL) {
      /*  if (strtolower($session->get('typ')) == 'administrateur') {*/
        $ingredient = $this->getDoctrine()
          ->getRepository('BDDBddClientBundle:Ingredient')->find($id);
        $idRecette = $ingredient->getRecette()->getId();
        $em = $this->getDoctrine()->getManager();
        $em->remove($ingredient);
        $em->flush();
        return $this->redirect($this->generateUrl('administration_admin_ajoutIngredient', array('id' => $idRecette)));
      /*  }else{
          return $this->redirect($this->generateUrl('accueil_accueil_homepage'));
        }*/
    }else{
      return $this->redirect($this->generateUrl('accueil_accueil_homepage'));
    }
  }

  public function modifierAction($id){
    $session = $this->getRequest()->getSession();
    if ($session->get('pren') != NULL) {
      /*  if (strtolower($session->get('typ')) == 'administrateur') {*/
        $recette = $this->getDoctrine()
          ->getRepository('BDDBddClientBundle:Recette')->find($id);
        $ingredients = $this->getDoctrine()
          ->getRepository('BDDBddClientBundle:Ingredient')->findByRecette($recette);
        $articles = $this->getDoctrine()
          ->getRepository('BDDBddClientBundle:Article')->findAll();
        return $this->render('AdministratorAdministrationAdminBundle:Recette:recetteModif.html.twig',
                array('recette' => $recette, 'ingredients' => $ingredients, 'articles' => $articles) );
     /* }else{
          return $this->redirect($this->generateUrl('accueil_accueil_homepage'));
        }*/
    }else{
      return $this->redirect($this->generateUrl('accueil_accueil_homepage'));
    }
  }

  public function modifierbisAction($id){
    $session = $this->getRequest()->getSession();
    if ($session->get('pren') != NULL) {
      /*  if (strtolower($session->get('typ')) == 'administrateur') {*/
      $recette = $this->getDoctrine()
        ->getRepository('BDDBddClientBundle:Recette')->find($id);
      $ingredients = $this->getDoctrine()
        ->getRepository('BDDBddClientBundle:Ingredient')->findByRecette($recette);
      $recettes = $this->getDoctrine()
        ->getRepository('BDDBddClientBundle:Recette')->findAll();

        $nom = $_POST['nom']; $tpsPrep = $_POST['tpsPrep'];
        $tpsCuisson = $_POST['tpsCuisson']; $prep = $_POST['preparation'];
        $photo = $_FILES['photo'];
        $message = "";
        \Doctrine\Common\Util\Debug::dump($prep);
        if ($nom == "" || strpos(htmlentities($nom, ENT_QUOTES), '&gt;') || strpos(htmlentities($nom, ENT_QUOTES), '&lt;')){
          $message .= "Nom incorrect. ";
        }
        if ($tpsPrep == "" || strpos(htmlentities($tpsPrep, ENT_QUOTES), '&gt;') || strpos(htmlentities($tpsPrep, ENT_QUOTES), '&lt;')){
          $message .= "Temps de Préparation incorrect. ";
        }
        if ($tpsCuisson == "" || strpos(htmlentities($tpsCuisson, ENT_QUOTES), '&gt;') || strpos(htmlentities($tpsCuisson, ENT_QUOTES), '&lt;')){
          $message .= "Temps de Cuisson incorrect. ";
        }
        if ($prep == "" || strpos(htmlentities($prep, ENT_QUOTES), '&gt;') || strpos(htmlentities($prep, ENT_QUOTES), '&lt;')){
          $message .= "Préparation incorrect. ";
        }
        if($photo['name'] != "" && strpos($photo['type'], "image") === false){
          $message .= "Format image incorrect. ";
        }

        foreach($ingredients as $ing){
          $nomI = $_POST['nom'.$ing->getId()];
          $quantite = $_POST['quantite'.$ing->getId()];
          $article = $_POST['choix'.$ing->getId()];
          $okay = true;
          if ($nom == "" || strpos(htmlentities($nom, ENT_QUOTES), '&gt;') || strpos(htmlentities($nom, ENT_QUOTES), '&lt;')){
            $okay = false;
          }
          if ($quantite == "" || strpos(htmlentities($quantite, ENT_QUOTES), '&gt;') || strpos(htmlentities($quantite, ENT_QUOTES), '&lt;')){
            $okay = false;
          }
          if ($okay){
            $ing->setNom($nomI);
            $ing->setQuantite($quantite);
            if ($article == NULL){
              $ing6>setArticle(null);
            }else{
              $art = $this->getDoctrine()
                ->getRepository('BDDBddClientBundle:Article')->find($article);
              $ing->setArticle($art);
            }
          }
        }

        if ($message == ""){
          if($photo['name'] != ""){
            $fileName = $photo['name'];
            $dir = $this->getRequest()->server->get('DOCUMENT_ROOT') . $this->getRequest()->getBasePath() . "/images/";
            move_uploaded_file($photo["tmp_name"], $dir.$fileName.time());
            unlink($dir.$recette->getPhoto());
            $recette->setPhoto($fileName.time());
          }


          if ($_POST['visible'] == "oui"){
            foreach($recettes as $rec){
              if ($rec->getVisible()){
                $rec->setVisible(false);
              }
            }
            $recette->setVisible(true);
          }else{
            $recette->setVisible(false);
          }
          $recette->setNom($nom);
          $recette->setTpsPrep($tpsPrep);
          $recette->setTpsCuisson($tpsCuisson);
          $recette->setPreparation($prep);
          $em = $this->getDoctrine()->getManager();
          $em->flush();
        }

      return $this->redirect($this->generateUrl('administration_admin_modifierRecette', array('id' => $id)));
     /* }else{
          return $this->redirect($this->generateUrl('accueil_accueil_homepage'));
        }*/
    }else{
      return $this->redirect($this->generateUrl('accueil_accueil_homepage'));
    }
  }

  public function supprimerAction($id){
    $session = $this->getRequest()->getSession();
    if ($session->get('pren') != NULL) {
      /*  if (strtolower($session->get('typ')) == 'administrateur') {*/
        $recette = $this->getDoctrine()
          ->getRepository('BDDBddClientBundle:Recette')->find($id);
        $ingredients = $this->getDoctrine()
          ->getRepository('BDDBddClientBundle:Ingredient')->findByRecette($recette);
        $em = $this->getDoctrine()->getManager();
        foreach($ingredients as $ing){
          $em->remove($ing);
        }
        unlink($this->getRequest()->server->get('DOCUMENT_ROOT').
                $this->getRequest()->getBasePath() . "/images/".$recette->getPhoto());
        $em->remove($recette);
        $em->flush();
        return $this->redirect($this->generateUrl('administrator_administration_admin_gerer_Recettes'));
      /*  }else{
          return $this->redirect($this->generateUrl('accueil_accueil_homepage'));
        }*/
    }else{
      return $this->redirect($this->generateUrl('accueil_accueil_homepage'));
    }
  }
}
