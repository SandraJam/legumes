<?php

namespace Accueil\AccueilBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Accueil\AccueilBundle\Entity\Contact;
use Accueil\AccueilBundle\Form\ContactType;

class ContactController extends Controller
{
    public function contactAction()
    {
      $contact = new Contact();
      $form = $this->createForm(new ContactType(), $contact);

      $request = $this->getRequest();
      if ($request->getMethod() == 'POST') {
        $form->bind($request);

        if ($form->isValid()) {
          $message = \Swift_Message::newInstance()
            ->setSubject('Formulaire')
            ->setFrom('leslegumesduvaldeloire@gmail.com')
            ->setTo($this->container->getParameter('accueil_accueil.emails.contact_email'))
            ->setBody($this->renderView('AccueilAccueilBundle::contactEmail.txt.twig', array('contact' => $contact)));
            $this->get('mailer')->send($message);

            $this->get('session')->getFlashBag()->Add('notice', 'Votre message a été envoyé!');

            return $this->redirect($this->generateUrl('accueil_accueil_contact'));
        }
    }

    return $this->render('AccueilAccueilBundle::contact.html.twig', array(
        'form' => $form->createView()
    ));
  }

  public function compteAction(){
    $session = $this->getRequest()->getSession();
    if ($session->get('users') != NULL){
      $user = $this->getDoctrine()
                    ->getRepository('BDDBddClientBundle:Utilisateurs')
                    ->find($session->get('id'));
      if ($user != NULL) {
        $commandes = $this->getDoctrine()
                      ->getRepository('BDDBddClientBundle:Commande')
                      ->findByUtilisateurs($user);
        return $this->render('AccueilAccueilBundle::compte.html.twig', array(
            'user' => $user, 'commandes' => $commandes
        ));
      }else{
        return $this->redirect($this->generateUrl('accueil_accueil_homepage'));
      }
    } else {
      return $this->redirect($this->generateUrl('accueil_accueil_homepage'));
    }
  }

  public function modifCompteAction() {
    $session = $this->getRequest()->getSession();
    if ($session->get('users') != NULL){
      $user = $this->getDoctrine()
                    ->getRepository('BDDBddClientBundle:Utilisateurs')
                    ->find($session->get('id'));
      if ($user != NULL) {
          $message = "";
          if ($_POST['password'] != "" && ! strpos(htmlentities($_POST['password'], ENT_QUOTES), ';')){
            $user->setPwd($_POST['password']);
          }
          if($_POST['adresse'] != "" && ! strpos(htmlentities($_POST['adresse'], ENT_QUOTES), ';')){
            $user->setAdresse($_POST['adresse']);
          }
          if($_POST['ville'] != "" && ! strpos(htmlentities($_POST['ville'], ENT_QUOTES), ';')){
            $user->setVille($_POST['ville']);
          }
          if(strlen($_POST['codepostal']) == 5 && ctype_digit($_POST['codepostal'])){
            $user->setCodePostal($_POST['codepostal']);
          }
          if(strlen($_POST['telephone']) == 10 && ctype_digit($_POST['telephone'])){
            $user->setTel($_POST['telephone']);
          }
          if(filter_var($_POST['mail'], FILTER_VALIDATE_EMAIL)){
            $user->setEmail($_POST['mail']);
          }
          if(isset($_POST['abo'])){
            $user->setEstInscrit(true);
          }else{
            $user->setEstInscrit(false);
          }
          $em = $this->getDoctrine()->getManager();
          $em->flush();
        return $this->redirect($this->generateUrl('accueil_accueil_compte'));
      }else{
        return $this->redirect($this->generateUrl('accueil_accueil_homepage'));
      }
    } else {
      return $this->redirect($this->generateUrl('accueil_accueil_homepage'));
    }
  }

}
