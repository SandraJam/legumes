<?php

namespace Administrator\Administration\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('AdministratorAdministrationAdminBundle:Default:index.html.twig', array('name' => $name));
    }
     public function pageAdminAccueilAction(){
         return $this->render('AdministratorAdministrationAdminBundle:Default:pagePrincAdmin.html.twig');
    }
}
