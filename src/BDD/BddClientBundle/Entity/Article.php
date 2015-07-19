<?php

namespace BDD\BddClientBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Article
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="BDD\BddClientBundle\Entity\ArticleRepository")
 */
class Article
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
     * @ORM\Column(name="descr", type="string", length=255)
     */
    private $descr;

    /**
     * @var string
     *
     * @ORM\Column(name="recommandation", type="string", length=255)
     */
    private $recommandation;

    /**
     * @var string
     *
     * @ORM\Column(name="photos", type="string", length=255)
     */
    private $photos;

    /**
      * @ORM\ManyToOne(targetEntity="Categorie")
      * @ORM\JoinColumn(name="categorie_id", referencedColumnName="id")
      */
    private $categorie;

    /**
     * @var string
     *
     * @ORM\Column(name="qtiteVente", type="string")
     */
    private $qtiteVente;

    /**
     * @var integer
     *
     * @ORM\Column(name="qtiteStock", type="integer")
     */
    private $qtiteStock;

    /**
     * @var integer
     *
     * @ORM\Column(name="commandeMax", type="integer")
     */
    private $commandeMax;

    /**
     * @var float
     *
     * @ORM\Column(name="prix", type="float")
     */
    private $prix;

    /**
     * @var boolean
     *
     * @ORM\Column(name="bonplan", type="boolean", nullable=true)
     */
    private $bonplan;


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
     * @return Article
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
     * Set descr
     *
     * @param string $descr
     * @return Article
     */
    public function setDescr($descr)
    {
        $this->descr = $descr;

        return $this;
    }

    /**
     * Get descr
     *
     * @return string
     */
    public function getDescr()
    {
        return $this->descr;
    }

    /**
     * Set recommandation
     *
     * @param string $recommandation
     * @return Article
     */
    public function setRecommandation($recommandation)
    {
        $this->recommandation = $recommandation;

        return $this;
    }

    /**
     * Get recommandation
     *
     * @return string
     */
    public function getRecommandation()
    {
        return $this->recommandation;
    }

    /**
     * Set photos
     *
     * @param string $photos
     * @return Article
     */
    public function setPhotos($photos)
    {
        $this->photos = $photos;

        return $this;
    }

    /**
     * Get photos
     *
     * @return string
     */
    public function getPhotos()
    {
        return $this->photos;
    }

    /**
     * Set categorie
     *
     * @param string $categorie
     * @return Article
     */
    public function setCategorie($categorie)
    {
        $this->categorie = $categorie;

        return $this;
    }

    /**
     * Get categorie
     *
     * @return string
     */
    public function getCategorie()
    {
        return $this->categorie;
    }

    /**
     * Set qtiteVente
     *
     * @param string $qtiteVente
     * @return Article
     */
    public function setQtiteVente($qtiteVente)
    {
        $this->qtiteVente = $qtiteVente;

        return $this;
    }

    /**
     * Get qtiteVente
     *
     * @return string
     */
    public function getQtiteVente()
    {
        return $this->qtiteVente;
    }

    /**
     * Set qtiteStock
     *
     * @param integer $qtiteStock
     * @return Article
     */
    public function setQtiteStock($qtiteStock)
    {
        $this->qtiteStock = $qtiteStock;

        return $this;
    }

    /**
     * Get qtiteStock
     *
     * @return integer
     */
    public function getQtiteStock()
    {
        return $this->qtiteStock;
    }

    /**
     * Set commandeMax
     *
     * @param integer $commandeMax
     * @return Article
     */
    public function setCommandeMax($commandeMax)
    {
        $this->commandeMax = $commandeMax;

        return $this;
    }

    /**
     * Get commandeMax
     *
     * @return integer
     */
    public function getCommandeMax()
    {
        return $this->commandeMax;
    }

    /**
     * Set prix
     *
     * @param float $prix
     * @return Article
     */
    public function setPrix($prix)
    {
        $this->prix = $prix;

        return $this;
    }

    /**
     * Get prix
     *
     * @return float
     */
    public function getPrix()
    {
        return $this->prix;
    }


    /**
     * Set bonplan
     *
     * @param boolean $bonplan
     * @return Article
     */
    public function setBonplan($bonplan)
    {
        $this->bonplan = $bonplan;
        return $this;
    }
    /**
     * Get bonplan
     *
     * @return boolean
     */
    public function isBonplan()
    {
        return $this->bonplan;
    }
}
