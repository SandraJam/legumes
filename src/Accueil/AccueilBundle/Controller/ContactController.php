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
            // Perform some action, such as sending an email

            // Redirect - This is important to prevent users re-posting
            // the form if they refresh the page
            return $this->redirect($this->generateUrl('accueil_accueil_contact'));
        }
    }

    return $this->render('AccueilAccueilBundle::contact.html.twig', array(
        'form' => $form->createView()
    ));
    }

}
