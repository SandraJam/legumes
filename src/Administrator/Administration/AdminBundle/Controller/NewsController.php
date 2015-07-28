<?php

namespace Administrator\Administration\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use BDD\BddClientBundle\Entity\News;

class NewsController extends Controller
{
    /* Page administration des News */
    public function indexAction(){
      $session = $this->getRequest()->getSession();
      if ($session->get('pren') != NULL) {
          if (strtolower($session->get('typ')) == 'administrateur') {
            $news = $this->getDoctrine()
                          ->getRepository('BDDBddClientBundle:News')
                          ->findAll();
            return $this->render('AdministratorAdministrationAdminBundle:News:newsAdmin.html.twig', array(
              'news' => $news
            ));
          }else{
            return $this->redirect($this->generateUrl('accueil_accueil_homepage'));
          }
      }else{
        return $this->redirect($this->generateUrl('accueil_accueil_homepage'));
      }
    }

    /* Ajout d'une News */
    public function ajoutAction(Request $request){
      $session = $this->getRequest()->getSession();
      if ($session->get('pren') != NULL) {
        if (strtolower($session->get('typ')) == 'administrateur') {
        $new = new News();
        $form = $this->createFormBuilder($new)
           ->add('titre','text')
           ->add('descr','textarea')
           ->add('visible','choice', array(
               'choices' => array(true => 'Oui', false => 'Non'),
               'expanded' => true,
               'multiple' => false))
           ->add('photo','file')
           ->add('colorT', 'choice', array(
                  'choices'   => array(
                    'black' => 'Noir',
                    'red' => 'Rouge',
                    'green' => 'Vert',
                    'orange' => 'Orange',
                    'brown' => 'Marron',
                    'blue' => 'Bleu')))
           ->add('colorC', 'choice', array(
                   'choices'   => array(
                     'black' => 'Noir',
                     'red' => 'Rouge',
                     'green' => 'Vert',
                     'orange' => 'Orange',
                     'brown' => 'Marron',
                     'blue' => 'Bleu')))
           ->add('valider','submit')
           ->add('annuler','reset')
           ->getForm();
        $form->handleRequest($request);

        if($form->isValid()) {
          $new = $form -> getData();
          $new->setDate(new \DateTime());
          $message = "";
          if ($new->getTitre() == "" || strpos(htmlentities($new->getTitre(), ENT_QUOTES), '&gt;') || strpos(htmlentities($new->getTitre(), ENT_QUOTES), '&lt;')){
            $message .= "Titre incorrect. ";
          }
          if ($new->getDescr() == "" || strpos(htmlentities($new->getDescr(), ENT_QUOTES), '&gt;') || strpos(htmlentities($new->getDescr(), ENT_QUOTES), '&lt;')){
            $message .= "Jour de Semaine incorrect. ";
          }
          if(!$new->getPhoto()->getSize()){
            $message .= "Photos trop grande!";
          }

          if ($message == ""){
            $tableauCarIndesirable = array(" ", "-", "#", "~", "\t", "\n", "\r", "\0", "\x0B", "\xA0");
            $uploadedFile = $new -> getPhoto();
            $fileName = str_replace($tableauCarIndesirable, "", $uploadedFile -> getClientOriginalName());
            $dir = $this->getRequest()->server->get('DOCUMENT_ROOT') . $this->getRequest()->getBasePath() . "/images/";
            $uploadedFile->move($dir, $fileName.time());
            $new->setPhoto($fileName.time());

            $em = $this->getDoctrine()->getManager();
            $em->persist($new);
            $em->flush();
            return $this->redirect($this->generateUrl('administrator_news_index'));
          }else{
            $request->getSession()->getFlashBag()->add('notice', $message);
          }
        }
        return $this->render('AdministratorAdministrationAdminBundle:News:newsAjout.html.twig',
              array('form' => $form->createView()) );
          }else{
            return $this->redirect($this->generateUrl('accueil_accueil_homepage'));
          }
      }else{
        return $this->redirect($this->generateUrl('accueil_accueil_homepage'));
      }
    }

    /* Supprimer une News */
    public function suppAction($id){
      $session = $this->getRequest()->getSession();
      if ($session->get('pren') != NULL) {
          if (strtolower($session->get('typ')) == 'administrateur') {
            $new = $this->getDoctrine()
            ->getRepository('BDDBddClientBundle:News')->find($id);
            $em = $this->getDoctrine()->getManager();
            $em->remove($new);
            $em->flush();
            return $this->redirect($this->generateUrl('administrator_news_index'));
          }else{
            return $this->redirect($this->generateUrl('accueil_accueil_homepage'));
          }
      }else{
        return $this->redirect($this->generateUrl('accueil_accueil_homepage'));
      }
    }

    /* Modifier une News */
    public function modifAction($id){
      $session = $this->getRequest()->getSession();
      if ($session->get('pren') != NULL) {
         if (strtolower($session->get('typ')) == 'administrateur') {
           $new = $this->getDoctrine()
              ->getRepository('BDDBddClientBundle:News')->find($id);
          return $this->render('AdministratorAdministrationAdminBundle:News:newsModif.html.twig',
                  array('news' => $new));
        }else{
            return $this->redirect($this->generateUrl('accueil_accueil_homepage'));
          }
      }else{
        return $this->redirect($this->generateUrl('accueil_accueil_homepage'));
      }
    }

    /* Valider modification News */
    public function modifbisAction($id){
      $session = $this->getRequest()->getSession();
      if ($session->get('pren') != NULL) {
          if (strtolower($session->get('typ')) == 'administrateur') {
            $new = $this->getDoctrine()
            ->getRepository('BDDBddClientBundle:News')->find($id);

            $titre = $_POST['titre'];
            $descr = $_POST['descr'];
            $colorC = $_POST['colorC'];
            $colorT = $_POST['colorT'];
            $photo = $_FILES['photo'];
            $message="";

            if ($titre == "" || strpos(htmlentities($titre, ENT_QUOTES), '&gt;') || strpos(htmlentities($titre, ENT_QUOTES), '&lt;')){
              $message .= "Titre incorrect. ";
            }
            if ($descr == "" || strpos(htmlentities($descr, ENT_QUOTES), '&gt;') || strpos(htmlentities($descr, ENT_QUOTES), '&lt;')){
              $message .= "Description incorrect. ";
            }
            if($photo['size'] == 0 && $photo['name'] != ""){
              $message .= "Photos trop grande!";
            }

        if ($message == ""){
          if($photo['name'] != ""){
            $tableauCarIndesirable = array(" ", "-", "#", "~", "\t", "\n", "\r", "\0", "\x0B", "\xA0");
            $fileName = str_replace($tableauCarIndesirable, "", $photo['name']);
            $dir = $this->getRequest()->server->get('DOCUMENT_ROOT') . $this->getRequest()->getBasePath() . "/images/";
            move_uploaded_file($photo["tmp_name"], $dir.$fileName.time());
            unlink($dir.$new->getPhoto());
            $new->setPhoto($fileName.time());
          }


          if (isset($_POST['visible'])){
            $new->setVisible(true);
          }else{
            $new->setVisible(false);
          }
          $new->setTitre($titre);
          $new->setDescr($descr);
          $new->setColorC($colorC);
          $new->setColorT($colorT);

          $em = $this->getDoctrine()->getManager();
          $em->flush();
        }

        return $this->redirect($this->generateUrl('administrator_news_index'));

        }else{
            return $this->redirect($this->generateUrl('accueil_accueil_homepage'));
          }
      }else{
        return $this->redirect($this->generateUrl('accueil_accueil_homepage'));
      }
    }
}
