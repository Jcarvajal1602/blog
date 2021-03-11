<?php

namespace App\Entity;

use App\Repository\PostsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PostsRepository::class)
 */
class Posts
{
    const POST_EXITOSO = 'Se ha publicado el post exitosamente';
    const POST_EDITADO_EXITOSO = 'Se ha editado el post exitosamente';
    const POST_ELIMINANDO_EXITOSO ='Se ha eliminado el post exitosamente';
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $titulo;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $foto;

    /**
     * @ORM\Column(type="datetime")
     */
    private $fecha_publicacion;



    /* CREAR RELACION ENTRE USER Y POSTS */
    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="posts")
     */
    protected $user;

    /**
     * @ORM\Column(type="string", length=80000)
     */
    private $contenido;

    public function __construct(){
        $this->fecha_publicacion = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitulo(): ?string
    {
        return $this->titulo;
    }

    public function setTitulo(string $titulo): self
    {
        $this->titulo = $titulo;

        return $this;
    }

    public function getFoto(): ?string
    {
        return $this->foto;
    }

    public function setFoto(string $foto): self
    {
        $this->foto = $foto;

        return $this;
    }

    public function getFechaPublicacion(): ?\DateTimeInterface
    {
        return $this->fecha_publicacion;
    }

    public function setFechaPublicacion(\DateTimeInterface $fecha_publicacion): self
    {
        $this->fecha_publicacion = $fecha_publicacion;

        return $this;
    }

    public function getContenido(): ?string
    {
        return $this->contenido;
    }

    public function setContenido(string $contenido): self
    {
        $this->contenido = $contenido;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

        /**
     * @param mixed $user
     */

    public function setUser($user): void
    {
        $this->user = $user;
    }
}
