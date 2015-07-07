<?php

namespace Accueil\AccueilBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use BDD\BddClientBundle\Entity\Utilisateurs;
use Administrator\Administrator\AdminBundle\Ressources;

class AccueilController extends Controller
{
    public function indexAction()
    {
        return $this->render('AccueilAccueilBundle::index.html.twig');
    }

    public function contactAction()
    {
        return $this->render('AccueilAccueilBundle::contact.html.twig');
    }

    public function formulaireConnexionAction(Request $request){
		$session = $this->getRequest()->getSession();
        if ($session->get('users') != NULL){
        	$session->set('users', NULL);
			return $this->redirect($this->generateUrl('accueil_accueil_homepage'));
            //si la session est vide
        }else{
    		$connexion = new Utilisateurs();
    	   	$form = $this->createFormBuilder($connexion)
    		    ->add('login','text')
    		    ->add('pwd','password')
		        ->add('valider','submit')
    		    ->add('annuler','reset')
    		    ->getForm()
    	    ;
        	$form->handleRequest($request);
            if($form->isValid()) {
               	$connexion = $form -> getData();
                //on cherche en base qu'il existe
                $user = $this->getDoctrine()
            		->getRepository('BDDBddClientBundle:Utilisateurs')
            		->findOneBy(array('login' => $connexion->getLogin(), 'pwd' => $connexion->getPwd()));
                    //si on l'a trouvé on me met dans la session
                if($user !=NULL){
                    $session->set('typ', $user->getType());
                    $session->set('logi', $user->getLogin());
                    $session->set('pren', $user->getPrenom());
                    $session->set('users', "present");
                    //si je suis un client
                    if(strtolower($user->getType()) != strtolower("ADMINISTRATEUR")){
                       return $this->redirect($this->generateUrl('accueil_accueil_homepage'));
                    }else{
                        //Page d'administration
                        return $this->redirect($this->generateUrl('goadministrator_administration'));
                    }
                     //l'Utilisateur n'exi""""""éste pas on va donc le faire s'enregistrer
                     return $this->redirect($this->generateUrl('inscriptionClient'));
                }else{
                    // on met la session à null
                    $session->set('users', NULL);
                    //on redirige vers la page d'inscriptigon
                     return $this->redirect($this->generateUrl('formConnexion'));
                }
            }
	       return $this->render('AccueilAccueilBundle::formLogin.html.twig',
            array('form' => $form->createView()) );
        }
    }
    public function inscriptionAction(Request $request){
        $session = $this->getRequest()->getSession();
        if ($session->get('users')!= NULL){
            $session->set('users')==NULL;
        }else{
            // on lance le formulaire pour ajout d'un utilisateur en base
            $connexion = new Utilisateurs();
            $form = $this->createFormBuilder($connexion)
                ->add('nom','text')
                ->add('prenom','text')
                ->add('login','text')
                ->add('pwd','password')
        		->add('adresse','text')
        		->add('codePostal','number')
        		->add('ville','text')
        		->add('dateNaissance','birthday')
        		->add('tel','number')
        		->add('email','email')
                ->add('valider','submit')
                ->add('annuler','reset')
                ->getForm()
            ;
            $form->handleRequest($request);

            if($form->isValid()){
                //Verification du numéro de téléphone
                if(count($form->getData()->getTel()) != 9 ){
                    //erreur du numéro de téléphone
                       $request->getSession()->getFlashBag()->add('notice', 'Le numéro de téléphone n\'est pas valide .');
                }
                if(count($form->getData()->getCodePostal()) != 5 ){
                    //erreur du codePostal
                       $request->getSession()->getFlashBag()->add('notice', 'Le codde postal n\'est pas valide n\'est pas valide .');
                }
                $connexion = $form -> getData();
                $user = $this->getDoctrine()
                    ->getRepository('BDDBddClientBundle:Utilisateurs')
                    ->findOneBy(array('login' => $connexion->getLogin(), 'pwd' => $connexion->getPwd()));

                if($user !=NULL){
                      $request
                            ->getSession()
                            ->getFlashBag()
                            ->add('notice','Le login existe déjà, veuillez en essayer un autre. ');
                }else{
                    $em = $this->getDoctrine()->getManager();
                    $connexion->setType("client");
                    //TODO  vérification
                   //IDEE en fonction du code postal, proposer les villes associées

                    $em->persist($connexion);
                    $em->flush();
                    if ($request->isMethod('POST')) {
                      $request->getSession()->getFlashBag()->add('notice', 'L utilisateur à été enregistré.');
                        if ($session->get('users') != NULL){
                               $session->set('typ', $user->getType());
                               $session->set('logi', $user->getLogin());
                               $session->set('pren', $user->getPrenom());
                            return $this->redirect($this->generateUrl('accueil_accueil_homepage'));
                        }
                    }
                }
            }
            return $this->render('AccueilAccueilBundle::inscriptionClient.html.twig', array('form' => $form->createView()) );
        }
    }
      public function pagePrincipaleAdmineAction(Request $request){
        $session = $this->getRequest()->getSession();
        if ($session->get('typ')==strtolower("ADMINISTRATEUR")){
               $session->set('typ', $user->getType());
               $session->set('logi', $user->getLogin());
               $session->set('pren', $user->getPrenom());
              return $this->redirect($this->generateUrl('goadministrator_administration'));
        }else{
              $session->set('users')==NULL;
            return $this->redirect($this->generateUrl('formConnexion'));
        }
    }
}
