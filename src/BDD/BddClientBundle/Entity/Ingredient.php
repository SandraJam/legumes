<?php

namespace BDD\BddClientBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Article
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="BDD\BddClientBundle\Entity\IngredientRepository")
 */
class Ingredient
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
     * @ORM\Column(name="quantite", type="string", length=255)
     */
    private $quantite;

    /**
     * @ORM\ManyToOne(targetEntity="Article")
     * @ORM\JoinColumn(name="article_id", referencedColumnName="id", nullable=true)
     */
   private $article;

   /**
    * @ORM\ManyToOne(targetEntity="Recette")
    * @ORM\JoinColumn(name="recette_id", referencedColumnName="id")
    */
  private $recette;

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
     * @return Ingredient
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
     * Set quantite
     *
     * @param string quantite
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
     * @param integer $qtiteVente
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
     * @return integer
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
     * Set quantite
     *
     * @param string $quantite
     * @return Ingredient
     */
    public function setQuantite($quantite)
    {
        $this->quantite = $quantite;

        return $this;
    }

    /**
     * Get quantite
     *
     * @return string
     */
    public function getQuantite()
    {
        return $this->quantite;
    }

    /**
     * Set article
     *
     * @param \BDD\BddClientBundle\Entity\Article $article
     * @return Ingredient
     */
    public function setArticle(\BDD\BddClientBundle\Entity\Article $article = null)
    {
        $this->article = $article;

        return $this;
    }

    /**
     * Get article
     *
     * @return \BDD\BddClientBundle\Entity\Article
     */
    public function getArticle()
    {
        return $this->article;
    }

    /**
     * Set recette
     *
     * @param \BDD\BddClientBundle\Entity\Recette $recette
     * @return Ingredient
     */
    public function setRecette(\BDD\BddClientBundle\Entity\Recette $recette)
    {
        $this->recette = $recette;

        return $this;
    }

    /**
     * Get recette
     *
     * @return \BDD\BddClientBundle\Entity\Recette
     */
    public function getRecette()
    {
        return $this->recette;
    }
}
