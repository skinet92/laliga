<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Perfil
 *
 * @ORM\Table(name="perfil")
 * @UniqueEntity("nombre", message = "Ya existe un perfil con este nombre.");
 * @ORM\Entity
 */
class Perfil
{
    /**
     * @var integer
     *
     * @ORM\Column(name="idperfil", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="perfil_idperfil_seq", allocationSize=1, initialValue=1)
     */
    private $idperfil;

    /**
     * @var string
     * @Assert\Regex(pattern="/^[a-zA-Z áéíóúñAÉÍÓÚÑ]+$/", message = "Este campo permite solo letras.")
     * @ORM\Column(name="nombre", type="string", length=50, nullable=true)
     */
    private $nombre;



    /**
     * Get idperfil
     *
     * @return integer
     */
    public function getIdperfil()
    {
        return $this->idperfil;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return Perfil
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    public function __toString()
    {
        return $this->getNombre();
    }
}
