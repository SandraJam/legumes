<?php

namespace BDD\BddClientBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Histoire
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="BDD\BddClientBundle\Entity\HistoireRepository")
 */
class Histoire
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
     * @ORM\Column(name="titre", type="string", length=255)
     */
    private $titre;

    /**
     * @var string
     *
     * @ORM\Column(name="colorTitre", type="string", length=25)
     */
    private $colorTitre;

    /**
     * @var string
     *
     * @ORM\Column(name="contenu", type="string", length=2500)
     */
    private $contenu;

     /**
     * @var string
     *
     * @ORM\Column(name="colorContenu", type="string", length=25)
     */
    private $colorContenu;

    /**
     * @var string
     *
     * @ORM\Column(name="lienPhoto", type="string", length=250)
     */
    private $lienPhoto;

    /**
     * @var string
     *
     * @ORM\Column(name="positionPhoto", type="string", length=1000)
     */
    private $positionPhoto;


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
     * Set titre
     *
     * @param string $titre
     * @return Histoire
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;

        return $this;
    }

    /**
     * Get titre
     *
     * @return string
     */
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * Set contenu
     *
     * @param string $contenu
     * @return Histoire
     */
    public function setContenu($contenu)
    {
        $this->contenu = $contenu;

        return $this;
    }

    /**
     * Get contenu
     *
     * @return string
     */
    public function getContenu()
    {
        return $this->contenu;
    }

    /**
     * Set lienPhoto
     *
     * @param string $lienPhoto
     * @return Histoire
     */
    public function setLienPhoto($lienPhoto)
    {
        $this->lienPhoto = $lienPhoto;

        return $this;
    }

    /**
     * Get lienPhoto
     *
     * @return string
     */
    public function getLienPhoto()
    {
        return $this->lienPhoto;
    }

    /**
     * Set colorTitre
     *
     * @param string $colorTitre
     * @return Histoire
     */
    public function setColorTitre($colorTitre)
    {
        $this->colorTitre = $colorTitre;

        return $this;
    }

    /**
     * Get colorTitre
     *
     * @return string
     */
    public function getColorTitre()
    {
        return $this->colorTitre;
    }

    /**
     * Set colorContenu
     *
     * @param string $colorContenu
     * @return Histoire
     */
    public function setColorContenu($colorContenu)
    {
        $this->colorContenu = $colorContenu;

        return $this;
    }

    /**
     * Get colorContenu
     *
     * @return string
     */
    public function getColorContenu()
    {
        return $this->colorContenu;
    }

    /**
     * Set positionPhoto
     *
     * @param string $positionPhoto
     * @return Histoire
     */
    public function setPositionPhoto($positionPhoto)
    {
        $this->positionPhoto = $positionPhoto;

        return $this;
    }

    /**
     * Get positionPhoto
     *
     * @return string
     */
    public function getPositionPhoto()
    {
        return $this->positionPhoto;
    }
}
