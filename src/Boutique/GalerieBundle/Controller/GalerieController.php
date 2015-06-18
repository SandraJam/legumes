<?php

namespace Boutique\GalerieBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class GalerieController extends Controller
{
    public function indexAction()
    {
        return $this->render('BoutiqueGalerieBundle::categorie.html.twig');
    }
}
