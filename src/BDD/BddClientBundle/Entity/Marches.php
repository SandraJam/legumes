<?php

namespace BDD\BddClientBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Marches
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="BDD\BddClientBundle\Entity\MarchesRepository")
 */
class Marches
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
     * @ORM\Column(name="lieu", type="string", length=255)
     */
    private $lieu;

    /**
     * @var string
     *
     * @ORM\Column(name="jourSemaine", type="string", length=255)
     */
    private $jourSemaine;

    /**
     * @var string
     *
     * @ORM\Column(name="info", type="string", length=1000)
     */
    private $info;


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
     * Set lieu
     *
     * @param string $lieu
     * @return Marches
     */
    public function setLieu($lieu)
    {
        $this->lieu = $lieu;

        return $this;
    }

    /**
     * Get lieu
     *
     * @return string
     */
    public function getLieu()
    {
        return $this->lieu;
    }

    /**
     * Set jourSemaine
     *
     * @param string $jourSemaine
     * @return Marches
     */
    public function setJourSemaine($jourSemaine)
    {
        $this->jourSemaine = $jourSemaine;

        return $this;
    }

    /**
     * Get jourSemaine
     *
     * @return string
     */
    public function getJourSemaine()
    {
        return $this->jourSemaine;
    }
}
