<?php

namespace App\Entity;

use App\Repository\ClientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use Doctrine\ORM\Mapping as ORM;

/**
 * Client
 *
 * @ORM\Table(name="client")
 * @ORM\Entity(repositoryClass=ClientRepository::class)
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name = "type" , type="string")
 * @ORM\DiscriminatorMap({
 *      "Cli" = "Client",
 *     "Pro"="Profesionnel",
 *     "Part"="Particulier",

 * })
 */
abstract class Client
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="mail", type="string", length=255, nullable=false)
     */
    private $mail;

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(string $mail): self
    {
        $this->mail = $mail;

        return $this;
    }


    /**
     * @var string
     *
     * @ORM\Column(name="rue", type="string", length=255, nullable=true)
     */
    private $rue;

    /**
     * @var string
     *
     * @ORM\Column(name="cp", type="string", length=5, nullable=true)
     */
    private $cp;

    /**
     * @var string
     *
     * @ORM\Column(name="ville", type="string", length=255, nullable=true)
     */
    private $ville;

    /**
     * @var string
     *
     * @ORM\Column(name="pays", type="string", length=255, nullable=true)
     */
    private $pays;



    public function __construct()
    {
        $this->commandes = new ArrayCollection();
        $this->devis = new ArrayCollection();
        $this->reparations = new ArrayCollection();
        $this->echangeClients = new ArrayCollection();
        $this->interventions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getRue(): ?string
    {
        return $this->rue;
    }

    public function setRue(string $rue): self
    {
        $this->rue = $rue;

        return $this;
    }

    public function getCp(): ?string
    {
        return $this->cp;
    }

    public function setCp(string $cp): self
    {
        $this->cp = $cp;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(string $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    public function getPays(): ?string
    {
        return $this->pays;
    }

    public function setPays(string $pays): self
    {
        $this->pays = $pays;

        return $this;
    }





    public function __toString()
    {
        return $this->mail;
    }
}