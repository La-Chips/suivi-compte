<?php

namespace App\Entity;

use App\Repository\ParticulierRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ParticulierRepository::class)
 */
class Particulier extends Client
{




    /**
     * @var string|null
     *
     * @ORM\Column(name="telephone", type="string", length=20, nullable=true)
     */
    private $telephone;

    /**
     * @var string|null
     *
     * @ORM\Column(name="civilite", type="string", length=255, nullable=true)
     */
    private $civilite;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255, nullable=false)
     */
    private $nom;



    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=255, nullable=true)
     */
    private $prenom;



    public function getTelephone(): ?string
    {
        $tel =  $this->telephone;
        $tel = str_replace(' ', '', $tel);
        if (!str_contains(' ', $tel)) {
            $tel = str_split($tel, 2);
            $tel = implode(" ", $tel);
        }
        return $tel;
    }

    public function setTelephone(?string $telephone): self
    {

        $this->telephone = $telephone;

        return $this;
    }

    public function getCivilite(): ?string
    {
        return $this->civilite;
    }

    public function setCivilite(?string $civilite): self
    {
        $this->civilite = $civilite;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function __toString()
    {
        return $this->getId() . ' - ' . $this->nom . ' ' . $this->prenom;
    }
    public function getType()
    {
        return 'Particulier';
    }
}
