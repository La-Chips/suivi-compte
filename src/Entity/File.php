<?php

namespace App\Entity;

use App\Repository\FileRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FileRepository::class)
 */
class File
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
     * @ORM\Column(type="float", nullable=true)
     */
    private $size;

    /**
     * @ORM\ManyToOne(targetEntity=Folder::class, inversedBy="files")
     */
    private $folder;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $originalName;

    /**
     * @ORM\ManyToOne(targetEntity=ExtentionIcon::class, inversedBy="files")
     */
    private $extention;

    public function __construct($originalName, $uid, $size = null)
    {
        $this->name = $uid;
        $this->originalName = $originalName;
        $this->size = $size;
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

    public function getSize(): ?float
    {
        return $this->size;
    }

    public function setSize(?float $size): self
    {
        $this->size = $size;

        return $this;
    }

    public function getFolder(): ?Folder
    {
        return $this->folder;
    }

    public function setFolder(?Folder $folder): self
    {
        $this->folder = $folder;

        return $this;
    }

    public function getOriginalName(): ?string
    {
        return $this->originalName;
    }

    public function setOriginalName(string $originalName): self
    {
        $this->originalName = $originalName;

        return $this;
    }

    public function __toString()
    {
        return $this->getOriginalName();
    }

    public function getExtension(): ?ExtentionIcon
    {
        return $this->extention;
    }

    public function setExtension(?ExtentionIcon $extention): self
    {
        $this->extention = $extention;

        return $this;
    }

    public function getIcon()
    {
        if ($this->extention->isImage()) {
            echo '<img src="/folder/' . $this->getName() . '"  width="50" />';
        } else {

            echo '<i class="' . $this->extention->getIconClass() . '"></i>';
        }
    }

    public function getPath()
    {
        $path = $this->folder->getPathURL() . '/' . $this->getName();
        return $path;
    }
}