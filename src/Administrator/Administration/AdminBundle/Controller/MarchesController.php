<?php

namespace Administrator\Administration\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use BDD\BddClientBundle\Entity\Marches;

class MarchesController extends Controller
{
    public function indexAction(){
      $session = $this->getRequest()->getSession();
      if ($session->get('pren') != NULL) {
          if (strtolower($session->get('typ')) == 'administrateur') {
            $marches = $this->getDoctrine()
                          ->getRepository('BDDBddClientBundle:Marches')
                          ->findAll();
            return $this->render('AdministratorAdministrationAdminBundle:Marches:marchesAdmin.html.twig', array(
              'marches' => $marches
            ));
          }else{
            return $this->redirect($this->generateUrl('accueil_accueil_homepage'));
          }
      }else{
        return $this->redirect($this->generateUrl('accueil_accueil_homepage'));
      }
    }

    public function ajoutAction(Request $request){
      $session = $this->getRequest()->getSession();
      if ($session->get('pren') != NULL) {
          if (strtolower($session->get('typ')) == 'administrateur') {
        $marche = new Marches();
        $form = $this->createFormBuilder($marche)
           ->add('lieu','text')
           ->add('jourSemaine','text')
           ->add('info','textarea')
           ->add('valider','submit')
           ->add('annuler','reset')
           ->getForm();
        $form->handleRequest($request);

        if($form->isValid()) {
          $marche = $form -> getData();
          $message = "";
          if ($marche->getLieu() == "" || strpos(htmlentities($marche->getLieu(), ENT_QUOTES), '&gt;') || strpos(htmlentities($marche->getLieu(), ENT_QUOTES), '&lt;')){
            $message .= "Lieu incorrect. ";
          }
          if ($marche->getJourSemaine() == "" || strpos(htmlentities($marche->getJourSemaine(), ENT_QUOTES), '&gt;') || strpos(htmlentities($marche->getJourSemaine(), ENT_QUOTES), '&lt;')){
            $message .= "Jour de Semaine incorrect. ";
          }
          if ($marche->getInfo() == "" || strpos(htmlentities($marche->getInfo(), ENT_QUOTES), '&gt;') || strpos(htmlentities($marche->getInfo(), ENT_QUOTES), '&lt;')){
            $message .= "Informations incorrect. ";
          }

          if ($message == ""){
            $em = $this->getDoctrine()->getManager();
            $em->persist($marche);
            $em->flush();
            return $this->redirect($this->generateUrl('administrator_marches_index'));
          }else{
            $request->getSession()->getFlashBag()->add('notice', $message);
          }
        }
        return $this->render('AdministratorAdministrationAdminBundle:Marches:marchesAjout.html.twig',
              array('form' => $form->createView()) );
          }else{
            return $this->redirect($this->generateUrl('accueil_accueil_homepage'));
          }
      }else{
        return $this->redirect($this->generateUrl('accueil_accueil_homepage'));
      }
    }

    public function suppAction($id){
      $session = $this->getRequest()->getSession();
      if ($session->get('pren') != NULL) {
          if (strtolower($session->get('typ')) == 'administrateur') {
          $marche = $this->getDoctrine()
            ->getRepository('BDDBddClientBundle:Marches')->find($id);
          $em = $this->getDoctrine()->getManager();
          $em->remove($marche);
          $em->flush();
          return $this->redirect($this->generateUrl('administrator_marches_index'));
          }else{
            return $this->redirect($this->generateUrl('accueil_accueil_homepage'));
          }
      }else{
        return $this->redirect($this->generateUrl('accueil_accueil_homepage'));
      }
    }

    public function modifAction($id){
      $session = $this->getRequest()->getSession();
      if ($session->get('pren') != NULL) {
         if (strtolower($session->get('typ')) == 'administrateur') {
          $marches = $this->getDoctrine()
              ->getRepository('BDDBddClientBundle:Marches')->find($id);
          return $this->render('AdministratorAdministrationAdminBundle:Marches:marchesModif.html.twig',
                  array('marches' => $marches));
        }else{
            return $this->redirect($this->generateUrl('accueil_accueil_homepage'));
          }
      }else{
        return $this->redirect($this->generateUrl('accueil_accueil_homepage'));
      }
    }

    public function modifbisAction($id){
      $session = $this->getRequest()->getSession();
      if ($session->get('pren') != NULL) {
          if (strtolower($session->get('typ')) == 'administrateur') {
        $marche = $this->getDoctrine()
          ->getRepository('BDDBddClientBundle:Marches')->find($id);
        $ingredients = $this->getDoctrine();

          $lieu = $_POST['lieu']; $jourS = $_POST['jourS'];
          $info = $_POST['info'];
          $message = "";
          if ($marche->getLieu() == "" || strpos(htmlentities($marche->getLieu(), ENT_QUOTES), '&gt;') || strpos(htmlentities($marche->getLieu(), ENT_QUOTES), '&lt;')){
            $message .= "Lieu incorrect. ";
          }
          if ($marche->getJourSemaine() == "" || strpos(htmlentities($marche->getJourSemaine(), ENT_QUOTES), '&gt;') || strpos(htmlentities($marche->getJourSemaine(), ENT_QUOTES), '&lt;')){
            $message .= "Jour de Semaine incorrect. ";
          }
          if ($marche->getInfo() == "" || strpos(htmlentities($marche->getInfo(), ENT_QUOTES), '&gt;') || strpos(htmlentities($marche->getInfo(), ENT_QUOTES), '&lt;')){
            $message .= "Informations incorrect. ";
          }

          if ($message == ""){
            $marche->setLieu($lieu);
            $marche->setJourSemaine($jourS);
            $marche->setInfo($info);
            $em = $this->getDoctrine()->getManager();
            $em->flush();
          }
          return $this->redirect($this->generateUrl('administrator_marches_modif', array('id' => $id)));
        }else{
            return $this->redirect($this->generateUrl('accueil_accueil_homepage'));
          }
      }else{
        return $this->redirect($this->generateUrl('accueil_accueil_homepage'));
      }
    }
}
