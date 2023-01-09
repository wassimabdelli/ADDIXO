<?php

namespace App\Entity;

use App\Repository\PiecesRepository;
use Doctrine\ORM\Mapping as ORM;
use\Symfony\Component\Validator\Constraints as Assert;



/**
 * @ORM\Entity(repositoryClass=PiecesRepository::class)
 */
class Pieces
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message="il faut remplir ce champ")
     */
    private $prductnumber;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $societe;

    /**
     * @ORM\Column(type="date")
     */
    private $date;

    /**
     * @ORM\Column(type="string", length=50)
     
     */
    private $utilisateur;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
    
     */
    private $drawingnumber;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $productionnum;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message="il faut remplir ce champ")
     */
    private $typekeylist;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;
     
    

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrductnumber(): ?int
    {
        return $this->prductnumber;
    }

    public function setPrductnumber(int $prductnumber): self
    {
        $this->prductnumber = $prductnumber;

        return $this;
    }

    public function getSociete(): ?string
    {
        return $this->societe;
    }

    public function setSociete(?string $societe): self
    {
        $this->societe = $societe;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = new \DateTime();

        return $this;
    }

    public function getUtilisateur(): ?string
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(string $utilisateur): self
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }

    public function getDrawingnumber(): ?string
    {
        return $this->drawingnumber;
    }

    public function setDrawingnumber(?string $drawingnumber): self
    {
        $this->drawingnumber = $drawingnumber;

        return $this;
    }

    public function getProductionnum(): ?int
    {
        return $this->productionnum;
    }

    public function setProductionnum(?int $productionnum): self
    {
        $this->productionnum = $productionnum;

        return $this;
    }

    public function getTypekeylist(): ?int
    {
        return $this->typekeylist;
    }

    public function setTypekeylist(int $typekeylist): self
    {
        $this->typekeylist = $typekeylist;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }



}
