<?php

namespace Boutique\ArticleBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ArticleController extends Controller
{
    public function indexAction()
    {
        return $this->render('BoutiqueArticleBundle::article.html.twig');
    }
}
