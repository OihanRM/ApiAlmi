<?php

namespace App\Entity;

use App\Repository\AsignaturaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AsignaturaRepository::class)]
class Asignatura
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nombre = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $descripcion = null;

    #[ORM\Column(nullable: true)]
    private ?int $horas = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $profesor = null;

    /**
     * @var Collection<int, Curso>
     */
    #[ORM\ManyToMany(targetEntity: Curso::class, inversedBy: 'asignaturas')]
    private Collection $id_curso;

    public function __construct(?string $nombre = null, ?string $descripcion = null, ?int $horas = null, ?string $profesor = null)
    {
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
        $this->horas = $horas;
        $this->profesor = $profesor;
        $this->id_curso = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(?string $nombre): static
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(?string $descripcion): static
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    public function getHoras(): ?int
    {
        return $this->horas;
    }

    public function setHoras(?int $horas): static
    {
        $this->horas = $horas;

        return $this;
    }

    public function getProfesor(): ?string
    {
        return $this->profesor;
    }

    public function setProfesor(?string $profesor): static
    {
        $this->profesor = $profesor;

        return $this;
    }

    /**
     * @return Collection<int, Curso>
     */
    public function getIdCurso(): Collection
    {
        return $this->id_curso;
    }

    public function addIdCurso(Curso $idCurso): static
    {
        if (!$this->id_curso->contains($idCurso)) {
            $this->id_curso->add($idCurso);
        }

        return $this;
    }

    public function removeIdCurso(Curso $idCurso): static
    {
        $this->id_curso->removeElement($idCurso);

        return $this;
    }
}
