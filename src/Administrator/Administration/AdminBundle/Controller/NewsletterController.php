<?php

namespace Administrator\Administration\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use BDD\BddClientBundle\Entity\Utilisateurs;

class NewsletterController extends Controller
{

  public function redactionAction(){
    $session = $this->getRequest()->getSession();
    if ($session->get('pren') != NULL) {
        if (strtolower($session->get('typ')) == 'administrateur') {
        return $this->render('AdministratorAdministrationAdminBundle:Newsletter:redactionmail.html.twig');
       }
    }
    return $this->redirect($this->generateUrl('accueil_accueil_homepage'));
  }

  public function previsualisationAction(){
    $session = $this->getRequest()->getSession();
    if ($session->get('pren') != NULL) {
        if (strtolower($session->get('typ')) == 'administrateur') {
        $nbPar = $_POST['nb'];
        $mail = array();
        $subject = $_POST['sujet'];
        for($i=1; $i < $nbPar+1; $i++){
          $par1 = ""; $par2 = "";
          $titre = $_POST['titre'.$i];
          $contenu = $_POST['contenu'.$i];
          $image = $_FILES['image'.$i];
          if($image['name'] != ""){
            $tableauCarIndesirable = array(" ", "-", "#", "~", "\t", "\n", "\r", "\0", "\x0B", "\xA0");
            $fileName = str_replace($tableauCarIndesirable, "", $image['name']);
            $dir = $this->getRequest()->server->get('DOCUMENT_ROOT') . $this->getRequest()->getBasePath() . "/images/";
            $imageN = $fileName.time();
            move_uploaded_file($image["tmp_name"], $dir.$imageN);
          }

          $position = $_POST['posimage'.$i];
          $colorT = $_POST['colortitre'.$i];
          $colorC = $_POST['colorcontenu'.$i];

          $par1 .= "<h1 style='color: ".$colorT.";' >".$titre."</h1>";
          if($image['name'] != "" && $position == 'dessus'){
            $par1 .="<p style='text-align: center;'><img src='";
            $par2 .="' alt='image mail' style='width: 50%;'/></p><p style='color: ".$colorC.";' >".$contenu."</p>";
          }
          if($image['name'] != "" && $position == 'gauche'){
            $par1 .="<img src='";
            $par2 .="' alt='image mail' style='width: 50%; float: left;'/><p style='color: ".$colorC.";' >".$contenu."</p>";
          }
          if($image['name'] != "" && $position == 'droite'){
            $par1 .="<img src='";
            $par2 .= "' alt='image mail' style='width: 50%; float: right;'/><p style='color: ".$colorC.";' >".$contenu."</p>";
          }
          if($image['name'] != "" && $position == 'dessous'){
            $par1 .="<p style='color: ".$colorC.";' >".$contenu."</p><p style='text-align: center;'><img src='";
            $par2 .= "' alt='image mail' style='width: 50%;'/></p>";
          }
          $mail[] = [$par1, $dir.$imageN, $par2];


       }
              return $this->render('AdministratorAdministrationAdminBundle:Newsletter:previsualisation.html.twig', array('mail' => $mail));

    }
    return $this->redirect($this->generateUrl('accueil_accueil_homepage'));
    }
  }
}
