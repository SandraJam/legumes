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
                        ->findByType('administrateur');
                  return $this->render('AdministratorAdministrationAdminBundle:Gerer:gererAdminUser.html.twig',
                       array('listeSuperUser'=>$listeSuperUser));
            }else{
            }
            
            //si session vide on reenvoit vers la page d'accueil
        }else{
            return $this->redirect($this->generateUrl('accueil_accueil_homepage'));
        }
    }
    
    public function administrationDesUtilisateursAdminsAction(){
        
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


}
