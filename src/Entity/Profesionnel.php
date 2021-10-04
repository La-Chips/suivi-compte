<?php

namespace App\Entity;

use App\Repository\ProfesionnelRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProfesionnelRepository::class)
 */
class Profesionnel extends Client
{


    /**
     * @ORM\Column(type="string", length=255)
     */
    private $raison_social;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $contact;



    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $mail_2;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $tel_1;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $tel_2;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $SIRET;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @ORM\Column(type="float")
     */
    private $quota;




    public function getRaisonSocial(): ?string
    {
        return $this->raison_social;
    }

    public function setRaisonSocial(string $raison_social): self
    {
        $this->raison_social = $raison_social;

        return $this;
    }

    public function getContact(): ?string
    {
        return $this->contact;
    }

    public function setContact(?string $contact): self
    {
        $this->contact = $contact;

        return $this;
    }



    public function getMail2(): ?string
    {
        return $this->mail_2;
    }

    public function setMail2(?string $mail_2): self
    {
        $this->mail_2 = $mail_2;

        return $this;
    }

    public function getTelephone(): ?string
    {
        $tel =  $this->tel_1;
        $tel = str_replace(' ', '', $tel);
        if (!str_contains(' ', $tel)) {
            $tel = str_split($tel, 2);
            $tel = implode(" ", $tel);
        }
        return $tel;
    }
    public function getTel1(): ?string
    {
        $tel =  $this->tel_1;
        $tel = str_replace(' ', '', $tel);

        if (!str_contains(' ', $tel)) {
            $tel = str_split($tel, 2);
            $tel = implode(" ", $tel);
        }
        return $tel;
    }

    public function setTel1(?string $tel_1): self
    {
        $this->tel_1 = $tel_1;

        return $this;
    }

    public function getTel2(): ?string
    {
        $tel =  $this->tel_2;
        $tel = str_replace(' ', '', $tel);

        if (!str_contains(' ', $tel)) {
            $tel = str_split($tel, 2);
            $tel = implode(" ", $tel);
        }
        return $tel;
    }

    public function setTel2(?string $tel_2): self
    {
        $this->tel_2 = $tel_2;

        return $this;
    }

    public function getSIRET(): ?string
    {
        return $this->SIRET;
    }

    public function setSIRET(?string $SIRET): self
    {
        $this->SIRET = $SIRET;

        return $this;
    }
    public function getType()
    {
        return 'Professionnel';
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getQuota(): ?float
    {
        return $this->quota;
    }

    public function setQuota(float $quota): self
    {
        $this->quota = $quota;

        return $this;
    }

    public function __toString()
    {
        if ($this->raison_social != null) {
            return  $this->getId() . ' - ' . $this->raison_social;
        }
        return '';
    }
}