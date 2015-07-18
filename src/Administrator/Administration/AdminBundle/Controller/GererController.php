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
    public function choixActionsClientAction(){
        $session = $this->getRequest()->getSession();
        if ($session->get('pren') != NULL) {
            if (strtolower($session->get('typ')) == 'administrateur') {  
                return $this->render('AdministratorAdministrationAdminBundle:Gerer:ddeActionsClients.html.twig');
            }
        } 
    } 

    public function administrationDesUtilisateursAdminsAction(){
        $session = $this->getRequest()->getSession();
        if ($session->get('pren') != NULL) {
            if (strtolower($session->get('typ')) == 'administrateur') {  
                $button = $_POST['buttonName'];

                if ($button == "ChercherClients") {
                    //GOTO PAGE recherche client 
                }elseif ($button == "Maillist") {
                    //GOTO page envoyer mailist
                }elseif($button == "GopaAdm"){
                    //GOTO page accueil admin
                    return $this->redirect($this->generateUrl('administrator_administration_admin_homepage'));
                }else{
                    //GOTO homepage avec deco
                }
            }
        }
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
