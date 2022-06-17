<?php

namespace App\Entity;
use App\Repository\EmployeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EmployeRepository::class)]
class Employe extends User
{

    #[ORM\Column(type: 'string', length: 255,unique: true)]
    private $matricule;

    #[ORM\Column(type: 'string', length: 255)]
    private $nom;

    #[ORM\Column(type: 'string', length: 255)]
    private $prenom;

    #[ORM\Column(type: 'string', length: 255)]
    private $adresse;

    #[ORM\Column(type: 'string', length: 255)]
    private $telPro;

    #[ORM\Column(type: 'string', length: 255)]
    private $telPerso;

    #[ORM\Column(type: 'string')]
    private $dateEmbauche;

    #[ORM\OneToOne(targetEntity: ContactUrgence::class, cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable:true, onDelete:"SET NULL")]
    private $contactUrgence;

    #[ORM\ManyToOne(targetEntity: Poste::class)]
    #[ORM\JoinColumn(nullable:true, onDelete:"SET NULL")]
    private $poste;


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

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getTelPro(): ?string
    {
        return $this->telPro;
    }

    public function setTelPro(string $telPro): self
    {
        $this->telPro = $telPro;

        return $this;
    }

    public function getTelPerso(): ?string
    {
        return $this->telPerso;
    }

    public function setTelPerso(string $telPerso): self
    {
        $this->telPerso = $telPerso;

        return $this;
    }

    public function getDateEmbauche(): ?string
    {
        return $this->dateEmbauche;
    }

    public function setDateEmbauche(string $date): self
    {
        $this->dateEmbauche = $date;

        return $this;
    }
    public function getMatricule(): ?string
    {
        return $this->matricule;
    }

    public function setMatricule(string $matricule): self
    {
        $this->matricule = $matricule;

        return $this;
    }

    public function getContactUrgence(): ?ContactUrgence
    {
        return $this->contactUrgence;
    }

    public function setContactUrgence(?ContactUrgence $contactUrgence): self
    {
        $this->contactUrgence = $contactUrgence;

        return $this;
    }

    public function getPoste(): ?Poste
    {
        return $this->poste;
    }

    public function setPoste(?Poste $poste): self
    {
        $this->poste = $poste;

        return $this;
    }



}
