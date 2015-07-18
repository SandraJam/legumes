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
                return $this->redirect($this->generateUrl('administration_admin_PREresultatChercherClient'));
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
            if (!$user) {
                throw $this->createNotFoundException(
                    'Aucun utilisateur trouvé pour cet id : '.$user->getId()
                );
            }
            if($button == "Suppr"){
                $em->remove($user);
                $em->flush();
            }else if($button == "Modif"){
                //GOTO page de modification de user
            }
        }
    }

    public function preResultatchercheClientsAction(Request $request){
         $connexion = new Utilisateurs();
        $form = $this->createFormBuilder($connexion)
            ->add('nom','text',array('label' => 'form.nom','required' => false))
            ->add('ville','text',array('label' => 'form.ville','required' => false))
            ->add('codePostal','text',array('label' => 'form.codePostal','required' => false))
            ->add('valider','submit')
            ->getForm()
        ;
        
       //MINI MOTEUR DE RECHERCHE en Doctrine Query Language
        $form->handleRequest($request);
        if($form->isValid()){
            $varnom=$form->getData()->getNom();
            $varVille=$form->getData()->getVille();
            $varCP=$form->getData()->getCodePostal();
            $em = $this->getDoctrine()->getManager();
            $listeClients=NULL;
            if($varnom==NULL && $varVille==NULL && $varCP == NULL){
                //On fait un findAll
                $listeClients=$this->getDoctrine()->getRepository('BDDBddClientBundle:Utilisateurs')->findAll();
            }else if($varnom!=NULL && $varVille==NULL && $varCP == NULL){
                //find by nom
               $query =$em ->createQuery("SELECT u FROM BDDBddClientBundle:Utilisateurs u WHERE u.nom LIKE :key ORDER BY u.nom ASC");
               $query->setParameter('key','%'.$varnom.'%');
               $listeClients = $query->getResult();
       
            }else if($varnom==NULL && $varVille!=NULL && $varCP == NULL){
                //find by ville
               $query =$em ->createQuery("SELECT u FROM BDDBddClientBundle:Utilisateurs u WHERE u.ville LIKE :key ORDER BY u.ville ASC");
               $query->setParameter('key','%'.$varVille.'%');
               $listeClients = $query->getResult();
            }else if($varnom==NULL && $varVille==NULL && $varCP != NULL){
                //find by cp
               $query =$em ->createQuery("SELECT u FROM BDDBddClientBundle:Utilisateurs u WHERE u.codePostal LIKE :key ORDER BY u.codePostal ASC");
               $query->setParameter('key','%'.$varCP.'%');
               $listeClients = $query->getResult();
            }else if($varnom==NULL && $varVille!=NULL && $varCP != NULL){
                //find by ville et cp
               $query =$em ->createQuery("SELECT u FROM BDDBddClientBundle:Utilisateurs u WHERE (u.ville LIKE :key1) AND ( u.codePostal LIKE :key2 ) ORDER BY u.ville ASC");
               $query->setParameter('key1','%'.$varVille.'%');
               $query->setParameter('key2','%'.$varCP.'%');
               $listeClients = $query->getResult();
            }else if($varnom!=NULL && $varVille==NULL && $varCP != NULL){
                //find by nom et cp
                 $query =$em ->createQuery("SELECT u FROM BDDBddClientBundle:Utilisateurs u WHERE (u.nom LIKE :key1) AND ( u.codePostal LIKE :key2 ) ORDER BY u.nom ASC");
               $query->setParameter('key1','%'.$varnom.'%');
               $query->setParameter('key2','%'.$varCP.'%');
               $listeClients = $query->getResult();
            }else if($varnom!=NULL && $varVille!=NULL && $varCP == NULL){
                //find by nom et ville
               $query =$em ->createQuery("SELECT u FROM BDDBddClientBundle:Utilisateurs u WHERE (u.nom LIKE :key1) AND ( u.ville LIKE :key2 ) ORDER BY u.nom ASC");
               $query->setParameter('key1','%'.$varnom.'%');
               $query->setParameter('key2','%'.$varVille.'%');
               $listeClients = $query->getResult();
            }else{
                //findby nom, ville et cp
               $query =$em ->createQuery("SELECT u FROM BDDBddClientBundle:Utilisateurs u WHERE (u.nom LIKE :key1) AND ( u.ville LIKE :key2 ) AND ( u.codePostal LIKE :key3 ) ORDER BY u.nom ASC");
               $query->setParameter('key1','%'.$varnom.'%');
               $query->setParameter('key2','%'.$varVille.'%');
                $query->setParameter('key3','%'.$varCP.'%');
               $listeClients = $query->getResult();
            }
            return $this->render('AdministratorAdministrationAdminBundle:Gerer:gererAdminUser.html.twig',array('listClients' => $listeClients) );
        }
         return $this->render('AdministratorAdministrationAdminBundle:Gerer:gererChercherClients.html.twig',array('form' => $form->createView()) );
        
    }
    public function resultatchercheClientsAction(){
         return $this->render('AdministratorAdministrationAdminBundle:Gerer:pageResultatRecherche.html.twig');
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
