<?php

namespace App\Modele;

use App\Entity\Campus;

class FiltreSortie
{
    private ?Campus $campus=null;

    private ?string $nom=null;

    private ?\DateTime $datedebut=null;

    private ?\DateTime $datefin=null;

    private ?bool $inscrit=false;

    private ?bool $pasInscrit=false;

    private ?bool $organisateur=false;

    private ?bool $sortieFermees=false;

    private ?int $iduser=null;

    public function getPasInscrit(): ?bool
    {
        return $this->pasInscrit;
    }

    public function setPasInscrit(?bool $pasInscrit): void
    {
        $this->pasInscrit = $pasInscrit;
    }

    public function getIduser(): ?int
    {
        return $this->iduser;
    }

    public function setIduser(?int $iduser): void
    {
        $this->iduser = $iduser;
    }

    public function getSortieFermees(): ?bool
    {
        return $this->sortieFermees;
    }

    public function setSortieFermees(?bool $sortieFermees): void
    {
        $this->sortieFermees = $sortieFermees;
    }

    public function getDatedebut(): ?\DateTime
    {
        return $this->datedebut;
    }

    public function setDatedebut(?\DateTime $datedebut): void
    {
        $this->datedebut = $datedebut;
    }

    public function getDatefin(): ?\DateTime
    {
        return $this->datefin;
    }

    public function setDatefin(?\DateTime $datefin): void
    {
        $this->datefin = $datefin;
    }

    public function getCampus(): ?Campus
    {
        return $this->campus;
    }

    public function setCampus(?Campus $campus): void
    {
        $this->campus = $campus;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): void
    {
        $this->nom = $nom;
    }

    public function getInscrit(): ?bool
    {
        return $this->inscrit;
    }

    public function setInscrit(?bool $inscrit): void
    {
        $this->inscrit = $inscrit;
    }

    public function getOrganisateur(): ?bool
    {
        return $this->organisateur;
    }

    public function setOrganisateur(?bool $organisateur): void
    {
        $this->organisateur = $organisateur;
    }
}