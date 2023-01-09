<?php

namespace App\Entity;

use App\Repository\OperationRepository;
use Doctrine\ORM\Mapping as ORM;
use\Symfony\Component\Validator\Constraints as Assert; 

/**
 * @ORM\Entity(repositoryClass=OperationRepository::class)
 */
class Operation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $boucle;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *      
     */
    private $search;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Assert\NotBlank(message="il faut remplir ce champ")
     */
    private $searchid;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBoucle(): ?int
    {
        return $this->boucle;
    }

    public function setBoucle(?int $boucle): self
    {
        $this->boucle = $boucle;

        return $this;
    }

    public function getSearch(): ?string
    {
        return $this->search;
    }

    public function setSearch(?string $search): self
    {
        $this->search = $search;

        return $this;
    }

    public function getSearchid(): ?int
    {
        return $this->searchid;
    }

    public function setSearchid(?int $searchid): self
    {
        $this->searchid = $searchid;

        return $this;
    }
}
