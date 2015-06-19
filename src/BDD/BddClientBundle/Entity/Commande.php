<?php

namespace BDD\BddClientBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Commande
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="BDD\BddClientBundle\Entity\CommandeRepository")
 */
class Commande
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
     * @ORM\Column(name="NumCommande", type="string", length=255)
     */
    private $numCommande;

     /**
      * @ORM\OneToOne(targetEntity="Utilisateurs")
      * @ORM\JoinColumn(name="utilisateurs_id", referencedColumnName="id")
      */
    private $utilisateurs;

    /**
      * @ORM\OneToOne(targetEntity="Marches")
      * @ORM\JoinColumn(name="marches_id", referencedColumnName="id")
      */
    private $retirerMarches;

     /**
      * @ORM\ManyToOne(targetEntity="Article")
      * @ORM\JoinColumn(name="article_id", referencedColumnName="id")
      */
    private $listeLeg;

    /**
     * @var integer
     *
     * @ORM\Column(name="total", type="integer")
     */
    private $total;


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
     * Set numCommande
     *
     * @param string $numCommande
     * @return Commande
     */
    public function setNumCommande($numCommande)
    {
        $this->numCommande = $numCommande;

        return $this;
    }

    /**
     * Get numCommande
     *
     * @return string 
     */
    public function getNumCommande()
    {
        return $this->numCommande;
    }

    /**
     * Set utilisateurs
     *
     * @param string $utilisateurs
     * @return Commande
     */
    public function setUtilisateurs($utilisateurs)
    {
        $this->utilisateurs = $utilisateurs;

        return $this;
    }

    /**
     * Get utilisateurs
     *
     * @return string 
     */
    public function getUtilisateurs()
    {
        return $this->utilisateurs;
    }

    /**
     * Set retirerOu
     *
     * @param string $retirerOu
     * @return Commande
     */
    public function setRetirerOu($retirerOu)
    {
        $this->retirerOu = $retirerOu;

        return $this;
    }

    /**
     * Get retirerOu
     *
     * @return string 
     */
    public function getRetirerOu()
    {
        return $this->retirerOu;
    }

    /**
     * Set listeLeg
     *
     * @param string $listeLeg
     * @return Commande
     */
    public function setListeLeg($listeLeg)
    {
        $this->listeLeg = $listeLeg;

        return $this;
    }

    /**
     * Get listeLeg
     *
     * @return string 
     */
    public function getListeLeg()
    {
        return $this->listeLeg;
    }

    /**
     * Set total
     *
     * @param integer $total
     * @return Commande
     */
    public function setTotal($total)
    {
        $this->total = $total;

        return $this;
    }

    /**
     * Get total
     *
     * @return integer 
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * Set retirerMarches
     *
     * @param \BDD\BddClientBundle\Entity\Marches $retirerMarches
     * @return Commande
     */
    public function setRetirerMarches(\BDD\BddClientBundle\Entity\Marches $retirerMarches = null)
    {
        $this->retirerMarches = $retirerMarches;

        return $this;
    }

    /**
     * Get retirerMarches
     *
     * @return \BDD\BddClientBundle\Entity\Marches 
     */
    public function getRetirerMarches()
    {
        return $this->retirerMarches;
    }
}
