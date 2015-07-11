<?php

namespace BDD\BddClientBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Recette
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Recette
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="tpsPrep", type="string", length=255)
     */
    private $tpsPrep;

    /**
     * @var string
     *
     * @ORM\Column(name="tpsCuisson", type="string", length=255)
     */
    private $tpsCuisson;

    /**
     * @var string
     *
     * @ORM\Column(name="preparation", type="string", length=255)
     */
    private $preparation;

    /**
     * @var boolean
     *
     * @ORM\Column(name="visible", type="boolean")
     */
    private $visible;

    /**
     * @var string
     *
     * @ORM\Column(name="photo", type="string", length=255)
     */
    private $photo;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nom
     *
     * @param string $nom
     * @return Recette
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set tpsPrep
     *
     * @param string $tpsPrep
     * @return Recette
     */
    public function setTpsPrep($tpsPrep)
    {
        $this->tpsPrep = $tpsPrep;

        return $this;
    }

    /**
     * Get tpsPrep
     *
     * @return string
     */
    public function getTpsPrep()
    {
        return $this->tpsPrep;
    }

    /**
     * Set tpsCuisson
     *
     * @param string $tpsCuisson
     * @return Recette
     */
    public function setTpsCuisson($tpsCuisson)
    {
        $this->tpsCuisson = $tpsCuisson;

        return $this;
    }

    /**
     * Get tpsCuisson
     *
     * @return string
     */
    public function getTpsCuisson()
    {
        return $this->tpsCuisson;
    }

    /**
     * Set preparation
     *
     * @param string $preparation
     * @return Recette
     */
    public function setPreparation($preparation)
    {
        $this->preparation = $preparation;

        return $this;
    }

    /**
     * Get preparation
     *
     * @return string
     */
    public function getPreparation()
    {
        return $this->preparation;
    }

    /**
     * Set visible
     *
     * @param integer $visible
     * @return Recette
     */
    public function setVisible($visible)
    {
        $this->visible = $visible;

        return $this;
    }

    /**
     * Get visible
     *
     * @return integer
     */
    public function getVisible()
    {
        return $this->visible;
    }

    /**
     * Set photo
     *
     * @param string $photo
     * @return Recette
     */
    public function setPhoto($photo)
    {
        $this->photo = $photo;

        return $this;
    }

    /**
     * Get photo
     *
     * @return string
     */
    public function getPhoto()
    {
        return $this->photo;
    }
}
