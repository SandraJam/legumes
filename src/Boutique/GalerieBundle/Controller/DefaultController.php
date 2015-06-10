<?php

namespace Boutique\GalerieBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('BoutiqueGalerieBundle:Default:index.html.twig', array('name' => $name));
    }
}
