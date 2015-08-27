<?php

namespace Administrator\Administration\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use BDD\BddClientBundle\Entity\Marches;

class MarchesController extends Controller
{
    /* Page administration marché */
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

    /* Ajout d'un nouveau marché */
    public function ajoutAction(Request $request){
      $session = $this->getRequest()->getSession();
      if ($session->get('pren') != NULL) {
          if (strtolower($session->get('typ')) == 'administrateur') {
        $marche = new Marches();
        $form = $this->createFormBuilder($marche)
           ->add('lieu','text')
           ->add('jourSemaine', 'choice', array(
                  'choices'   => array(
                    'lundi' => 'Lundi',
                    'mardi' => 'Mardi',
                    'mercredi' => 'Mercredi',
                    'jeudi' => 'Jeudi',
                    'vendredi' => 'Vendredi',
                    'samedi' => 'Samedi',
                    'dimanche' => 'Dimanche'),
                    'multiple'  => true,
                    'expanded'  => true))
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
          if ($marche->getJourSemaine() == null || $marche->getJourSemaine() == "" ){
            $message .= "Jour de Semaine incorrect. ";
          }
          if ($marche->getInfo() == "" || strpos(htmlentities($marche->getInfo(), ENT_QUOTES), '&gt;') || strpos(htmlentities($marche->getInfo(), ENT_QUOTES), '&lt;')){
            $message .= "Informations incorrect. ";
          }

          if ($message == ""){
            $jour = $marche->getJourSemaine();
            $j = "";
            foreach($jour as $a){
              $j .= $a." ";
            }
            $marche->setJourSemaine($j);
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

    /* Supprimer un marché */
    public function suppAction($id){
      $session = $this->getRequest()->getSession();
      if ($session->get('pren') != NULL) {
          if (strtolower($session->get('typ')) == 'administrateur') {
          $marche = $this->getDoctrine()
            ->getRepository('BDDBddClientBundle:Marches')->find($id);
          $em = $this->getDoctrine()->getManager();

          $commandes = $this->getDoctrine()
            ->getRepository('BDDBddClientBundle:Commande')->findByRetirerMarches($marche);
          foreach($commandes as $com){
            $em->remove($com);
          }

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

    /* Modifie un marché */
    public function modifAction($id){
      $session = $this->getRequest()->getSession();
      if ($session->get('pren') != NULL) {
         if (strtolower($session->get('typ')) == 'administrateur') {
          $marches = $this->getDoctrine()
              ->getRepository('BDDBddClientBundle:Marches')->find($id);
          $jour = explode(' ', $marches->getJourSemaine());
          return $this->render('AdministratorAdministrationAdminBundle:Marches:marchesModif.html.twig',
                  array('marches' => $marches, 'jour' => $jour));
        }else{
            return $this->redirect($this->generateUrl('accueil_accueil_homepage'));
          }
      }else{
        return $this->redirect($this->generateUrl('accueil_accueil_homepage'));
      }
    }

    /* Valide la modification d'un marché */
    public function modifbisAction($id){
      $session = $this->getRequest()->getSession();
      if ($session->get('pren') != NULL) {
          if (strtolower($session->get('typ')) == 'administrateur') {
        $marche = $this->getDoctrine()
          ->getRepository('BDDBddClientBundle:Marches')->find($id);
        $ingredients = $this->getDoctrine();

          $lieu = $_POST['lieu'];
          $info = $_POST['info'];

          $jour = "";
          if (isset($_POST['lundi'])){
            $jour .= "lundi ";
          }
          if (isset($_POST['mardi'])){
            $jour .= "mardi ";
          }
          if (isset($_POST['mercredi'])){
            $jour .= "mercredi ";
          }
          if (isset($_POST['jeudi'])){
            $jour .= "jeudi ";
          }
          if (isset($_POST['vendredi'])){
            $jour .= "vendredi ";
          }
          if (isset($_POST['samedi'])){
            $jour .= "samedi ";
          }
          if (isset($_POST['dimanche'])){
            $jour .= "dimanche ";
          }

          $message = "";
          if ($lieu == "" || strpos(htmlentities($lieu, ENT_QUOTES), '&gt;') || strpos(htmlentities($lieu, ENT_QUOTES), '&lt;')){
            $message .= "Lieu incorrect. ";
          }
          if ($jour == ""){
            $message .= "Jour de Semaine incorrect. ";
          }
          if ($info == "" || strpos(htmlentities($info, ENT_QUOTES), '&gt;') || strpos(htmlentities($info, ENT_QUOTES), '&lt;')){
            $message .= "Informations incorrect. ";
          }

          if ($message == ""){
            $marche->setLieu($lieu);
            $marche->setInfo($info);
            $marche->setJourSemaine($jour);

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
