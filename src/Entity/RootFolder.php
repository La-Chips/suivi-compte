<?php

namespace App\Entity;

use App\Repository\RootFolderRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RootFolderRepository::class)
 */
class RootFolder
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity=Folder::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $rootFolder;

    /**
     * @ORM\OneToOne(targetEntity=Folder::class, inversedBy="rootFolder", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $folder;

    public function __construct($rootFolder,$folder)
    {
        $this->rootFolder = $rootFolder;
        $this->folder = $folder;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRootFolder(): ?Folder
    {
        return $this->rootFolder;
    }

    public function setRootFolder(Folder $rootFolder): self
    {
        $this->rootFolder = $rootFolder;

        return $this;
    }

    public function getFolder(): ?Folder
    {
        return $this->folder;
    }

    public function setFolder(Folder $folder): self
    {
        $this->folder = $folder;

        return $this;
    }
}
