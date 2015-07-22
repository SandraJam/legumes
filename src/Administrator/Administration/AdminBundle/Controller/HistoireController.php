<?php

namespace Administrator\Administration\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use BDD\BddClientBundle\Entity\Utilisateurs;

class HistoireController extends Controller
{
	public function pageAccueilGestionHistoireAction(){
		$session = $this->getRequest()->getSession();
		if ( ($session->get('pren') != NULL) && (strtolower($session->get('typ')) == 'administrateur') ) {
			$em = $this->getDoctrine()->getManager();
			$repository = $em
                        ->getRepository('BDDBddClientBundle:Histoire');
            $listeHistoire=$repository->findAll();
			return $this->render('AdministratorAdministrationAdminBundle:Gerer:gererHistoireAccueil.html.twig',array('listHistoire' => $listeHistoire));
		}else{
			return $this->redirect($this->generateUrl('accueil_accueil_homepage'));
		}		
	}

}
