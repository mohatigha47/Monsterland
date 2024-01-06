<?php

namespace App\Entity;

use App\Repository\MonstreRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: MonstreRepository::class)]
#[UniqueEntity(fields: ['nom'], message: 'Ce nom est déjà utilisé.')]
class Monstre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;


    #[ORM\Column(length: 255)]
    /**
     * @Assert\NotBlank
     * @Assert\Regex(
     *      pattern="/^[a-z]+$/",
     *      message="Le nom doit contenir uniquement des caractères alphabétiques en minuscules, sauf la première lettre."
     * )
     * @Assert\Length(
     *      min=2,
     *      max=255,
     *      minMessage="Le nom doit avoir au moins {{ limit }} caractères.",
     *      maxMessage="Le nom ne peut pas avoir plus de {{ limit }} caractères."
     * )
     */
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\Choice(choices={"Zombie", "Vampire", "Orque", "Titan", "Ogre", "Lutin"})
     */
    private ?string $type = null;

    #[ORM\Column]
    /**
     * @ORM\Column(type="integer")
     * @Assert\Range(
     *      min=0,
     *      max=100,
     *      notInRangeMessage="La puissance doit être comprise entre {{ min }} et {{ max }}."
     * )
     */
    private ?int $puissance = null;

    #[ORM\Column]
    /**
     * @ORM\Column(type="integer")
     * @Assert\GreaterThan(
     *      value=0,
     *      message="La taille doit être supérieure à 0."
     * )
     */
    private ?int $taille = null;

    #[ORM\ManyToOne (cascade:['persist'],inversedBy:"monstres")]
    private ?Royaume $royaume = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getPuissance(): ?int
    {
        return $this->puissance;
    }

    public function setPuissance(int $puissance): static
    {
        $this->puissance = $puissance;

        return $this;
    }

    public function getTaille(): ?int
    {
        return $this->taille;
    }

    public function setTaille(int $taille): static
    {
        $this->taille = $taille;

        return $this;
    }

    public function getRoyaume(): ?Royaume
    {
        return $this->royaume;
    }

    public function setRoyaume(?Royaume $royaume): static
    {
        $this->royaume = $royaume;

        return $this;
    }
}
