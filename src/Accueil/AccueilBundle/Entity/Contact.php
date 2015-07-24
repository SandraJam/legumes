<?php
// src/Accueil/AccueilBundle/Entity/Contact.php
namespace Accueil\AccueilBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;

class Contact
{
  protected $name;

  protected $email;

  protected $subject;

  protected $tel;

  protected $body;

  public function getName()
  {
      return $this->name;
  }

  public function setName($name)
  {
      $this->name = $name;
  }

  public function getEmail()
  {
      return $this->email;
  }

  public function setEmail($email)
  {
      $this->email = $email;
  }

  public function getSubject()
  {
      return $this->subject;
  }

  public function setSubject($subject)
  {
      $this->subject = $subject;
  }

  public function getBody()
  {
      return $this->body;
  }

  public function setBody($body)
  {
      $this->body = $body;
  }

  public function getTel()
  {
      return $this->tel;
  }

  public function setTel($tel)
  {
      $this->tel = $tel;
  }

  public static function loadValidatorMetadata(ClassMetadata $metadata)
  {
      $metadata->addPropertyConstraint('name', new NotBlank());

      $metadata->addPropertyConstraint('email', new Email());

      $metadata->addPropertyConstraint('subject', new NotBlank());

      $metadata->addPropertyConstraint( 'subject', new Length( array( 'min' => 5) ) );

      $metadata->addPropertyConstraint( 'subject', new Length( array( 'max' => 10) ) );

  }
}
