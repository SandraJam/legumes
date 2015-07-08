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
        return $this->render('AccueilAccueilBundle::compte.html.twig', array(
            'user' => $user
        ));
      }else{
        return $this->redirect($this->generateUrl('accueil_accueil_homepage'));
      }
    } else {
      return $this->redirect($this->generateUrl('accueil_accueil_homepage'));
    }
  }

  public function modifCompteAction() {
    // Revoir sécurité
    $session = $this->getRequest()->getSession();
    if ($session->get('users') != NULL){
      $user = $this->getDoctrine()
                    ->getRepository('BDDBddClientBundle:Utilisateurs')
                    ->find($session->get('id'));
      if ($user != NULL) {
        $tryAgain=false;
        if(count($_POST['telephone']) != 10 && preg_match("/^(\d\d\s){4}(\d\d)+\s$/",$_POST['telephone']) ){
            $tryAgain=true;
        }
        if(count($_POST['codepostal']) != 5 && !preg_match("/^[0-9]{5,5}$/",$_POST['codepostal'])){
            $tryAgain=true;
        }
        if ( !preg_match ( "/[a-zA-Z0-9_]+(\.[a-zA-Z0-9_]+)*\@[a-zA-Z0-9_]+(\.[a-zA-Z0-9_]+)*\.[a-zA-Z]{2,4}$/", $_POST['mail'] ) ){
            $tryAgain=true;
        }
        if (!$tryAgain){
          if ($_POST['password'] != ""){
            $user->setPwd($_POST['password']);
          }
          $user->setAdresse($_POST['adresse']);
          $user->setVille($_POST['ville']);
          $user->setCodePostal($_POST['codepostal']);
          $user->setTel($_POST['telephone']);
          $user->setEmail($_POST['mail']);
          $em = $this->getDoctrine()->getManager();
          $em->flush();
        }
        return $this->redirect($this->generateUrl('accueil_accueil_compte'));
      }else{
        return $this->redirect($this->generateUrl('accueil_accueil_homepage'));
      }
    } else {
      return $this->redirect($this->generateUrl('accueil_accueil_homepage'));
    }
  }

}
