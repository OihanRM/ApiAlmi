<?php

namespace App\Entity;

use App\Repository\CursoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CursoRepository::class)]
class Curso
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['curso:read', 'curso:write', 'curso:test'])]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['curso:read', 'curso:write',  'curso:test'])]
    private ?string $nombre = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['curso:read', 'curso:write', 'curso:test'])]
    private ?string $descripcion = null;



    /**
     * @var Collection<int, Asignatura>
     */
    #[ORM\ManyToMany(targetEntity: Asignatura::class, mappedBy: 'id_curso')]
    #[Groups(['curso:read', 'curso:write',  'curso:test'])]
    private Collection $asignaturas;

    public function __construct(?string $nombre = null, ?string $descripcion = null)
    {
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
        $this->asignaturas = new ArrayCollection();
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

    /**
     * @return Collection<int, Asignatura>
     */
    public function getAsignaturas(): Collection
    {
        return $this->asignaturas;
    }

    public function addAsignatura(Asignatura $asignatura): static
    {
        if (!$this->asignaturas->contains($asignatura)) {
            $this->asignaturas->add($asignatura);
            $asignatura->addIdCurso($this);
        }

        return $this;
    }

    public function removeAsignatura(Asignatura $asignatura): static
    {
        if ($this->asignaturas->removeElement($asignatura)) {
            $asignatura->removeIdCurso($this);
        }

        return $this;
    }
}
