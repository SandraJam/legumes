<?php

namespace Administrator\Administration\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use BDD\BddClientBundle\Entity\Utilisateurs;

class GererController extends Controller
{
    
    public function lesUtilisateursAdminsAction(){
        $warning = '';
        $session = $this->getRequest()->getSession();
        if ($session->get('pren') != NULL) {
            if (strtolower($session->get('typ')) == 'administrateur') {
                $repository = $this
                        ->getDoctrine()
                        ->getManager()
                        ->getRepository('BDDBddClientBundle:Utilisateurs');
                        
                $listeSuperUser = $this->getDoctrine()->getRepository('BDDBddClientBundle:Utilisateurs')
                        ->findByType('client');
                   
                  return $this->render('AdministratorAdministrationAdminBundle:Gerer:gererAdminUser.html.twig',
                       array('listeSuperUser'=>$listeSuperUser));
            }else{
            }
            
            //si session vide on reenvoit vers la page d'accueil
        }else{
            return $this->redirect($this->generateUrl('accueil_accueil_homepage'));
        }
    }
    /**
    * Envoie vers la page qui demande si on cherche un client ou si on fait un maillist
    */
    public function choixActionsClientsAction(){
        $session = $this->getRequest()->getSession();
        if ($session->get('pren') != NULL) {
            if (strtolower($session->get('typ')) == 'administrateur') {  
                return $this->render('AdministratorAdministrationAdminBundle:Gerer:ddeActionsClients.html.twig');
            }
        } 
    } 
    public function administrationDesUtilisateursAdminsAction(Request $request){
        $session = $this->getRequest()->getSession();
        if ($session->get('pren') != NULL) {
            if (strtolower($session->get('typ')) == 'administrateur') {  
                $button = $_POST['buttonName'];

                if ($button == "ChercherClients") {
                    //GOTO PAGE recherche client 
                $connexion = new Utilisateurs();
                $form = $this->createFormBuilder($connexion)
                    ->add('nom','text',array('label' => 'form.nom','required' => false))
                    ->add('ville','text',array('label' => 'form.ville','required' => false))
                    ->add('codePostal','text',array('label' => 'form.codePostal','required' => false))
                    ->add('valider','submit')
                    ->add('annuler','reset')
                    ->getForm()
                ;
                $form->handleRequest($request);
                if($form->isValid()){
                    return $this->redirect($this->generateUrl('administration_admin_resultatChercherClient'),array('form'=> $form));
                }
                return $this->render('AdministratorAdministrationAdminBundle:Gerer:gererChercherClients.html.twig',array('form' => $form->createView()) );
                
                }elseif ($button == "Maillist") {
                    //GOTO page envoyer mailist
                }elseif ($button == "GopaAdm") {
                    //GOTO page accueil admin
                    return $this->redirect($this->generateUrl('administrator_administration_admin_homepage'));
                }else{
                    //GOTO homepage avec deco
                }
            }
        }
    }
 //gere la modif ou la suppression d'un user sur la page gerer util
    public function gestionUtilisateursAction(){
        $session = $this->getRequest()->getSession();
        if ($session->get('pren') != NULL) {
            $userId = $_POST['userId'];
            $button = $_POST['buttonId'];
            $repository = $this
                        ->getDoctrine()
                        ->getManager()
                        ->getRepository('BDDBddClientBundle:Utilisateurs');
            $user = $repository->findOneById($userId);
            if (!$product) {
                throw $this->createNotFoundException(
                    'Aucun utilisateur trouvé pour cet id : '.$user->getId()
                );
            }
            if($button == "Suppr"){
                $em->remove($product);
                $em->flush();
            }else if($button == "Modif"){
                //GOTO page de modification de user
            }
        }
    }

    public function resultatchercheClientsAction(){

    }
    public function suivitDesCommandesAction(){
      
    }

	public function lesProduitsAction(){
      
    }

    public function lesCategoriesAction(){
      
    }

	public function lesNewsAction(){
      
    }
	
	public function lesRecettesAction(){
      
    }

	public function lesCoordsClientsAction(){
      
    }

    public function gererHistoire(){
      
    }
}
