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
     * @ORM\ManyToOne(targetEntity=Folder::class, inversedBy="folders")
     */
    private $root;

    /**
     * @ORM\OneToMany(targetEntity=Folder::class, mappedBy="root")
     */
    private $folders;








    public function __construct($name)
    {
        $this->files = new ArrayCollection();
        $this->name = $name;
        $this->rootFolders = new ArrayCollection();
        $this->folders = new ArrayCollection();
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



    public function getPath($route)
    {
        $route = substr($route, 0, -1);

        $path =
            $this->getName();

        $folder  = $this;
        while ($folder->getRoot() != null) {
            $folder = $folder->getRoot();
            echo '<a href="' . $route . $folder->getId() . '">' . $folder->getName() . '</a>';
            echo  ' <i class="fas fa-chevron-right"></i> ';
        }

        return $path;
    }

    public function getPathURL()
    {

        $path = '';

        $folder  = $this;
        while ($folder->getRoot() != null) {
            $folder = $folder->getRoot();
            $path .= '/' . $folder->getName();
        }

        return $path;
    }



    public function getRoot(): ?self
    {
        return $this->root;
    }

    public function setRoot(?self $root): self
    {
        $this->root = $root;

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getFolders(): Collection
    {
        return $this->folders;
    }

    public function addFolder(self $folder): self
    {
        if (!$this->folders->contains($folder)) {
            $this->folders[] = $folder;
            $folder->setRoot($this);
        }

        return $this;
    }

    public function removeFolder(self $folder): self
    {
        if ($this->folders->removeElement($folder)) {
            // set the owning side to null (unless already changed)
            if ($folder->getRoot() === $this) {
                $folder->setRoot(null);
            }
        }

        return $this;
    }
}