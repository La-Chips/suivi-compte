<?php

namespace App\Entity;

use App\Repository\FolderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FolderRepository::class)
 */
class Folder
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
    private $name;



    /**
     * @ORM\OneToMany(targetEntity=File::class, mappedBy="folder")
     */
    private $files;

    /**
     * @ORM\OneToOne(targetEntity=RootFolder::class, mappedBy="folder", cascade={"persist", "remove"})
     */
    private $rootFolder;


   

    public function __construct($name)
    {
        $this->folder = new ArrayCollection();
        $this->files = new ArrayCollection();
        $this->folders = new ArrayCollection();
        $this->name = $name;
        $this->rootFolder = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

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
            $file->setFolder($this);
        }

        return $this;
    }

    public function removeFile(File $file): self
    {
        if ($this->files->removeElement($file)) {
            // set the owning side to null (unless already changed)
            if ($file->getFolder() === $this) {
                $file->setFolder(null);
            }
        }

        return $this;
    }

 

    public function __toString()
    {
        return $this->name;
    }

    public function getRootFolder(): ?RootFolder
    {
        return $this->rootFolder;
    }

    public function setRootFolder(RootFolder $rootFolder): self
    {
        // set the owning side of the relation if necessary
        if ($rootFolder->getFolder() !== $this) {
            $rootFolder->setFolder($this);
        }

        $this->rootFolder = $rootFolder;

        return $this;
    }

}
