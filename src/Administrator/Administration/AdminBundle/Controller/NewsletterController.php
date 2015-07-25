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
        // Je supprimes les images des newsletter precedente!
        $tableauCarIndesirable = array(" ", "-", "#", "~", "\t", "\n", "\r", "\0", "\x0B", "\xA0");
        $dir = $this->getRequest()->server->get('DOCUMENT_ROOT') . $this->getRequest()->getBasePath() . "/images/";

        if (is_dir($dir)) {
          if ($dh = opendir($dir)) {
            while (($file = readdir($dh)) !== false) {
              if (strpos($file, "__mail")){
                unlink($dir.$file);
              }
            }
          closedir($dh);
          }
        }

        $nbPar = $_POST['nb'];
        $mail = array();
        $subject = $_POST['sujet'];

        $phraseMail = "";
        for($i=1; $i < $nbPar+1; $i++){
          $par1 = ""; $par2 = "";
          $titre = $_POST['titre'.$i];
          $contenu = $_POST['contenu'.$i];
          $image = $_FILES['image'.$i];
          if($image['name'] != ""){
            $fileName = str_replace($tableauCarIndesirable, "", $image['name']);
            $imageN = $fileName.time()."__mail";
            move_uploaded_file($image["tmp_name"], $dir.$imageN);
          }

          $position = $_POST['posimage'.$i];
          $colorT = $_POST['colortitre'.$i];
          $colorC = $_POST['colorcontenu'.$i];

          $par1 .= "<h1 style='color: ".$colorT."; text-align: center;' >".$titre."</h1>";
          if($image['name'] != "" && $position == 'dessus'){
            $par1 .="<p style='text-align: center;'><img src='";
            $par2 .="' alt='image mail' style='width: 40%;'/></p><p style='color: ".$colorC.";' >".$contenu."</p>";
          }
          if($image['name'] != "" && $position == 'gauche'){
            $par1 .="<img src='";
            $par2 .="' alt='image mail' style='width: 25%; float: left;'/><p style='color: ".$colorC.";' >".$contenu."</p>";
          }
          if($image['name'] != "" && $position == 'droite'){
            $par1 .="<img src='";
            $par2 .= "' alt='image mail' style='width: 25%; float: right;'/><p style='color: ".$colorC.";' >".$contenu."</p>";
          }
          if($image['name'] != "" && $position == 'dessous'){
            $par1 .="<p style='color: ".$colorC.";' >".$contenu."</p><p style='text-align: center;'><img src='";
            $par2 .= "' alt='image mail' style='width: 40%;'/></p>";
          }
          if($image['name'] == ""){
            $par1 .="<p style='color: ".$colorC.";' >".$contenu."</p><p style='text-align: center;'>";
            $par2 .= "</p>";
          }
          if ($image['name'] != ""){
            $mail[] = [$par1, 'images/'.$imageN, $par2];
            $phraseMail .= $par1."::".'images/'.$imageN."::".$par2."$$";
          }else{
            $mail[] = [$par1, "", $par2];
            $phraseMail .= $par1."::"." "."::".$par2."$$";
          }


       }

       return $this->render('AdministratorAdministrationAdminBundle:Newsletter:previsualisation.html.twig', array('mail' => $mail, 'phrase' => $phraseMail));
     }
    }
    return $this->redirect($this->generateUrl('accueil_accueil_homepage'));
  }

  public function validationAction(){
    $session = $this->getRequest()->getSession();
    if ($session->get('pren') != NULL) {
        if (strtolower($session->get('typ')) == 'administrateur') {
          $mail = $_POST['mail'];
          $a = explode('$$', $mail);

          $message = \Swift_Message::newInstance();
          $body = "<html><body>";
          $body2 = $body;
          for($i=0; $i < count($a)-1; $i++){
            $m = explode('::', $a[$i]);
            if (isset($m[0]) && $m[0] != NULL){
              $body .= $m[0];
              $body2 .=$m[0];
            }
            if (isset($m[1]) && $m[1] != NULL && $m[1] != " "){
              $body .= $message->embed(\Swift_Image::fromPath($m[1]));
            }
            if (isset($m[2]) && $m[2] != NULL){
              $body .= $m[2];
              $body2 .=$m[2];
            }
          }
          $body .= '</body></html>';

          $bodyTxt = str_replace(
            array('<body><html>', '</body></html>', "<h1 style='color: ", "; text-align: center;' >", "</h1>", "<p style='text-align: center;'><img src='", "' alt='image mail' style='width: 30%;'/></p><p style='color: ", ";' >", "</p>", "<img src='", "' alt='image mail' style='width: 30%; float: left;'/><p style='color: ", "' alt='image mail' style='width: 30%; float: right;'/><p style='color: ", "<p style='color: ", "</p><p style='text-align: center;'><img src='", "' alt='image mail' style='width: 50%;'/></p>" ),
             "", $body2);

            $users = $this->getDoctrine()
                          ->getRepository('BDDBddClientBundle:Utilisateurs')
                          ->findByEstInscrit(true);
             // VOIR LES USERS A QUI ENVOYR + BUG SI PAS D'IMAGE
          $dir = $this->getRequest()->server->get('DOCUMENT_ROOT') . $this->getRequest()->getBasePath();

          $message->setSubject('Les Légumes du Val de loire: Votre Newsletter')
            ->setFrom($this->container->getParameter('accueil_accueil.emails.contact_email'))
            ->setBody($body, 'text/html');

          foreach($users as $user){
            $message->setTo($user->getEmail());
            $this->get('mailer')->send($message);
          }

          return $this->render('AdministratorAdministrationAdminBundle:Newsletter:validation.html.twig');
        }
    }
    return $this->redirect($this->generateUrl('accueil_accueil_homepage'));
  }
}
