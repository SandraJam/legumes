<?php

namespace Accueil\AccueilBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use BDD\BddClientBundle\Entity\Utilisateurs;
use BDD\BddClientBundle\Entity\Recette;
use BDD\BddClientBundle\Entity\Ingredient;
use Administrator\Administrator\AdminBundle\Ressources;

class AccueilController extends Controller
{
    public function indexAction()
    {
        $recette = $this->getDoctrine()
        ->getRepository('BDDBddClientBundle:Recette')
        ->findOneByVisible(true);
        $ingredients = $this->getDoctrine()
        ->getRepository('BDDBddClientBundle:Ingredient')
        ->findByRecette($recette);
        return $this->render('AccueilAccueilBundle::index.html.twig', array('ingredients' => $ingredients, 'recette' => $recette));
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
    		    ->add('annuler','submit')
    		    ->getForm()
    	    ;
        	$form->handleRequest($request);
            if($form->isValid()) {
                $annuler = $form->get('annuler')->isClicked();
                if($annuler){
                    return $this->redirect($this->generateUrl('accueil_accueil_homepage'));
                }else{
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
                        $session->set('id', $user->getId());
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
            }
        }
	    return $this->render('AccueilAccueilBundle::formLogin.html.twig',
        array('form' => $form->createView()) );   
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
                ->add('codePostal','text')
        		->add('jour','number')
                ->add('mois','number')
                ->add('annee','number')
        		->add('ville','text')
        		->add('tel','text', array('label' => 'form.Annuler', 'required' => false))
        		->add('email','email')
                ->add('valider','submit')
                ->add('annuler','submit',array('label' => 'form.Annuler'))
                ->getForm()
            ;
            $form->handleRequest($request);

            if($form->isValid()){
                $annuler = $form->get('annuler')->isClicked();
                if($annuler){
                    return $this->redirect($this->generateUrl('accueil_accueil_homepage'));
                }else{
                    $tryAgain=false;
	                   $form->getData()->setEstInscrit(true);
                    //Verification du numéro de téléphone
                    if(count($form->getData()->getTel()) != 10 && preg_match("/^(\d\d\s){4}(\d\d)+\s$/",$form->getData()->getTel()) ){
                        //erreur du numéro de téléphone
                        $tryAgain=true;
                           $request->getSession()->getFlashBag()->add('notice', 'Le numéro de téléphone n\'est pas valide .');
                    }
                    if(count($form->getData()->getCodePostal()) != 5 && !preg_match("/^[0-9]{5,5}$/",$form->getData()->getCodePostal())){
                        //erreur du codePostal
                        $tryAgain=true;
                           $request->getSession()->getFlashBag()->add('notice', 'Le code postal n\'est pas valide .');
                    }
                    if(count($form->getData()->getJour()) <= 2 && preg_match("/[0-3][0-9]/",$form->getData()->getJour())&&
                        (strval($form->getData()->getJour())<1 || strval($form->getData()->getJour())>32)&&
                        count($form->getData()->getMois()) <= 2 && preg_match("([0-9]{2,2})",$form->getData()->getMois()) &&
                        ((strval($form->getData()->getMois())<1) || strval($form->getData()->getMois())>12)&&
                            count($form->getData()->getAnnee()) == 4 && preg_match("([0-3]{2,2})",$form->getData()->getAnnee())&&
                        (!(strval($form->getData()->getAnnee())<1900) || strval($form->getData()->getAnnee())> 2020)){
                        //erreur de date de naissance TODO CHANGER 2020 en VARIABLE GLOBAL QUI TE PREND LA DATE DU JOUR MAIS QUE L'ANNEE
                        $tryAgain=true;
                           $request->getSession()->getFlashBag()->add('notice', 'La date de naissance n\'est pas valide,
                            le jour et le mois doivent contenir 1 ou 2 chiffres et l\'année 4 chiffres.');
                    }
                    if ( !preg_match ( "/[a-zA-Z0-9_]+(\.[a-zA-Z0-9_]+)*\@[a-zA-Z0-9_]+(\.[a-zA-Z0-9_]+)*\.[a-zA-Z]{2,4}$/", $form->getData()->getEmail() ) ){
                      $tryAgain=true;
                      $request->getSession()->getFlashBag()->add('notice', 'l\'adresse mail n\'est pas valide.');
                    }
                    $connexion = $form -> getData();

                            $birth = ($connexion->getJour()."-".$connexion->getMois()."-".$connexion->getAnnee());
                    $connexion->setDateNaissance(strval($birth));
                    $connexion->setType("client");
                    $user = $this->getDoctrine()
                        ->getRepository('BDDBddClientBundle:Utilisateurs')
                        ->findOneBy(array('login' => $connexion->getLogin(), 'nom' => $connexion->getNom()));
                            if($user !=NULL){
                          $request
                                ->getSession()
                                ->getFlashBag()
                                ->add('notice','Le login existe déjà, veuillez en essayer un autre. ');
                                $tryAgain=true;
                    }else{
                        if(!$tryAgain){
                            $em = $this->getDoctrine()->getManager();
                            $connexion->setType("client");
                            //TODO  vérification
                            //IDEE en fonction du code postal, proposer les villes associées
                                    $em->persist($connexion);
                            $em->flush();
                            if ($request->isMethod('POST')) {
                              $request->getSession()->getFlashBag()->add('notice', 'L utilisateur à été enregistré.');
                                if ($session->get('users') == NULL){
                                       $session->set('typ', $connexion->getType());
                                       $session->set('logi', $connexion->getLogin());
                                       $session->set('pren', $connexion->getPrenom());
                                       $session->set('users', "present");
                                    return $this->redirect($this->generateUrl('accueil_accueil_homepage'));
                                }else{
                                    return $this->redirect($this->generateUrl('accueil_accueil_homepage'));
                                }
                            }
                        }else{
                         return $this->render('AccueilAccueilBundle::inscriptionClient.html.twig', array('form' => $form->createView()) );
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
               $session->set('users', "present");
              return $this->redirect($this->generateUrl('goadministrator_administration'));
        }else{
              $session->set('users')==NULL;
            return $this->redirect($this->generateUrl('formConnexion'));
        }
    }
}
