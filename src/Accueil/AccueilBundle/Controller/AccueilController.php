<?php

namespace Accueil\AccueilBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use BDD\BddClientBundle\Entity\Utilisateurs;

class AccueilController extends Controller
{
    public function indexAction()
    {
        return $this->render('AccueilAccueilBundle::index.html.twig');
    }
    public function formulaireConnexionAction(Request $request)
    {
    		//teste si la session n'est pas vide
		$session = $this->getRequest()->getSession();
        //si un utilisateur est déjà connecté alors ça le déconnecte
        if ($session->get('users') != NULL){
        	$session->set('users', NULL);
            //redirection vers la page d'accueil
			return $this->redirect($this->generateUrl('accueil_accueil_homepage'));
            //si la session est vide  
        }else{
            //on lance le formulaire de login
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
                    if($user->getType() != "ADMINISTRATEUR"){
                	   $session->set('type', $user->getType());
                       $session->set('login', $user->getLogin());
                       return $this->redirect($this->generateUrl('accueil_accueil_homepage'));
                    }
                }else{
                             \Doctrine\Common\Util\Debug::dump($form->getData()->getLogin());
                }
            }else{
                //erreur le formulaire n'est pas valide
                  \Doctrine\Common\Util\Debug::dump($form->isValid());
            }
	       return $this->render('AccueilAccueilBundle::formLogin.html.twig', 
            array('form' => $form->createView()) );
        }
    }
}
