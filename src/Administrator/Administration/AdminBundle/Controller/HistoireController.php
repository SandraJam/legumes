<?php

namespace Administrator\Administration\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use BDD\BddClientBundle\Entity\Utilisateurs;
use BDD\BddClientBundle\Entity\Histoire;

use Administrator\Administrator\AdminBundle\Ressources;

class HistoireController extends Controller
{
	public function pageAccueilGestionHistoireAction(){
		$session = $this->getRequest()->getSession();
		if ( ($session->get('pren') != NULL) && (strtolower($session->get('typ')) == 'administrateur') ) {
			$em = $this->getDoctrine()->getManager();
			$repository = $em
                        ->getRepository('BDDBddClientBundle:Histoire');
            $listeHistoire=$repository->findAll();
			return $this->render('AdministratorAdministrationAdminBundle:Histoire:gererHistoireAccueil.html.twig',array('listHistoire' => $listeHistoire));
		}else{
			return $this->redirect($this->generateUrl('accueil_accueil_homepage'));
		}		
	}

	public function modificationActionHistoireAction(){
		$session = $this->getRequest()->getSession();
		if ( ($session->get('pren') != NULL) && (strtolower($session->get('typ')) == 'administrateur') ) {
			$articleId = $_POST['articleId'];
           	$button = $_POST['buttonId'];

			if($button == "Modifier"){
				
			}
		   	if($button == "Supprimer"){
				
			}
		}else{
			return $this->redirect($this->generateUrl('accueil_accueil_homepage'));
		}		
	}
	public function ajouterHistoireAction(){
		$session = $this->getRequest()->getSession();
		if ( ($session->get('pren') != NULL) && (strtolower($session->get('typ')) == 'administrateur') ) {
			return $this->render('AdministratorAdministrationAdminBundle:Histoire:formAjouterHistoire.html.twig');
        }
    }

    public function ajouterHistoireBisAction(){
		$session = $this->getRequest()->getSession();
		if ( ($session->get('pren') != NULL) && (strtolower($session->get('typ')) == 'administrateur') ) {
			return $this->render('AdministratorAdministrationAdminBundle:Histoire:formAjouterHistoire.html.twig');
        }
    }
		
}
