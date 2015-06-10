<?php

namespace Boutique\ArticleBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('BoutiqueArticleBundle:Default:index.html.twig', array('name' => $name));
    }
}
