<?php

namespace App\Entity;

use App\Repository\ExtentionIconRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ExtentionIconRepository::class)
 */
class ExtentionIcon
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $extension;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $icon_class;

    /**
     * @ORM\OneToMany(targetEntity=File::class, mappedBy="extention")
     */
    private $files;

    public function __construct()
    {
        $this->files = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getExtension(): ?string
    {
        return $this->extension;
    }

    public function setExtension(string $extension): self
    {
        $this->extension = $extension;

        return $this;
    }

    public function getIconClass(): ?string
    {
        return $this->icon_class;
    }

    public function setIconClass(string $icon_class): self
    {
        $this->icon_class = $icon_class;

        return $this;
    }

    /**
     * @return Collection|File[]
     */
    public function getFiles(): Collection
    {
        return $this->files;
    }

    public function addFile(File $file): self
    {
        if (!$this->files->contains($file)) {
            $this->files[] = $file;
            $file->setExtention($this);
        }

        return $this;
    }

    public function removeFile(File $file): self
    {
        if ($this->files->removeElement($file)) {
            // set the owning side to null (unless already changed)
            if ($file->getExtention() === $this) {
                $file->setExtention(null);
            }
        }

        return $this;
    }

    public function isImage()
    {
        return in_array($this->getExtension(), array('.png', '.jpg', '.jpeg', '.gif', '.tif', '.tiff'));
    }

    public function __toString()
    {
        return $this->getExtension();
    }
}