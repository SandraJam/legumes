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

	public function modificationActionHistoireAction(Request $request){
		$session = $this->getRequest()->getSession();
		if ( ($session->get('pren') != NULL) && (strtolower($session->get('typ')) == 'administrateur') ) {
			$articleId = $_POST['articleId'];
           	$button = $_POST['buttonId'];
			if($button == "Modifier"){
				return $this->redirect($this->generateUrl('administrator_administration_admin_modifierHistoire',
					array('articleId' => $articleId)));
			}
		   	if($button == "Supprimer"){
				$histoire=$this->getDoctrine()->getRepository('BDDBddClientBundle:Histoire')->find($articleId);
		   		if($histoire !=NULL){
		   			$em = $this->getDoctrine()->getManager();
            		$em->remove($histoire);
            		$em->flush();
            		return $this->redirect($this->generateUrl('administrator_administration_admin_gerer_notre_histoire'));
				}else{
					return $this->redirect($this->generateUrl('administrator_administration_admin_gerer_notre_histoire'));
				}
			}
		}else{
			return $this->redirect($this->generateUrl('accueil_accueil_homepage'));
		}
	}
	public function ajouterHistoireAction(Request $request){
		$session = $this->getRequest()->getSession();
		if ( ($session->get('pren') != NULL) && (strtolower($session->get('typ')) == 'administrateur') ) {
			$histoire = new Histoire();
         	$form = $this->createFormBuilder($histoire)
         		->add('titre','text')
         		->add('colorTitre', 'choice', array(
         		 'choices'   => array(
         		   'black' => 'Noir',
         		   'red' => 'Rouge',
         		   'green' => 'Vert',
         		   'orange' => 'Orange',
         		   'brown' => 'Marron',
         		   'blue' => 'Bleu')))
         		->add('contenu','textarea')
         		->add('colorContenu', 'choice', array(
         		 'choices'   => array(
         		   'black' => 'Noir',
         		   'red' => 'Rouge',
         		   'green' => 'Vert',
         		   'orange' => 'Orange',
         		   'brown' => 'Marron',
         		   'blue' => 'Bleu')))
         		->add('lienPhoto','file')
    			->add('positionPhoto', 'choice', array(
         		   'choices'   => array(
         		    	'haut' => 'Au dessus',
       		    		'bas' => 'Au dessous',
         		    	'gauche' => 'A gauche',
         		    	'droite' => 'A droite')))
           ->add('valider','submit')
           ->add('annuler','reset')
         		->getForm();
        	$form->handleRequest($request);

        	if($form->isValid()){
				$histoire=$form->getData();
					if(!$histoire->getLienPhoto()->getSize()){
        				$request->getSession()->getFlashBag()->add('notice', 'La photo est trop volumineuse.');
            		}else{
						$tableauCarIndesirable = array(" ", "-", "#", "~", "\t", "\n", "\r", "\0", "\x0B", "\xA0");
            			$uploadedFile = $histoire -> getLienPhoto();
            			$fileName = str_replace($tableauCarIndesirable, "", $uploadedFile -> getClientOriginalName());
            			$dir = $this->getRequest()->server->get('DOCUMENT_ROOT') . $this->getRequest()->getBasePath() . "/images/";
            			$uploadedFile->move($dir, $fileName.time());
            			$histoire->setLienPhoto($fileName.time());

            			$em = $this->getDoctrine()->getManager();
            			$em->persist($histoire);
            			$em->flush();
            			return $this->redirect($this->generateUrl('administrator_administration_admin_gerer_notre_histoire'));
        			}
        	}
        	return $this->render(
        		'AdministratorAdministrationAdminBundle:Histoire:formAjouterHistoire.html.twig',
        		array('form' => $form->createView()));
        }else{
            return $this->redirect($this->generateUrl('accueil_accueil_homepage'));
        }
    }

    public function modifierHistoireAction(Request $request, $articleId){
    	$session = $this->getRequest()->getSession();
		if (($session->get('pren') != NULL) && (strtolower($session->get('typ')) == 'administrateur')) {
    		$histoire=$this->getDoctrine()->getRepository('BDDBddClientBundle:Histoire')->find($articleId);
			if($histoire!=NULL){
        		$form = $this->createFormBuilder($histoire)
        			->add('titre','text')
        			->add('colorTitre', 'choice', array(
        			 'choices'   => array(
        			   'black' => 'Noir',
        			   'red' => 'Rouge',
        			   'green' => 'Vert',
        			   'orange' => 'Orange',
        			   'brown' => 'Marron',
        			   'blue' => 'Bleu')))
        			->add('contenu','textarea')
        			->add('colorContenu', 'choice', array(
        			 'choices'   => array(
        			   'black' => 'Noir',
        			   'red' => 'Rouge',
        			   'green' => 'Vert',
        			   'orange' => 'Orange',
        			   'brown' => 'Marron',
        			   'blue' => 'Bleu')))
        			->add('lienPhoto','file', array(
        				'data_class' => null
        				))
    				->add('positionPhoto', 'choice', array(
        			   'choices'   => array(
        			    	'haut' => 'Au dessus',
       						'bas' => 'Au dessous',
        			    	'gauche' => 'A gauche',
        				    'droite' => 'A droite')))
        			->add('valider','submit')
        			->add('annuler','reset')
        			->getForm();
        		$form->handleRequest($request);
			
        		if($form->isValid()){
					$histoire=$form->getData();
					//TODO a finir
				}
				return $this->render('AdministratorAdministrationAdminBundle:Histoire:modificationHistoire.html.twig',array('form' => $form->createView()));
        	}else{
					return $this->redirect($this->generateUrl('administrator_administration_admin_gerer_notre_histoire'));
			}

    	}else{
            return $this->redirect($this->generateUrl('accueil_accueil_homepage'));
        }
	}

}
