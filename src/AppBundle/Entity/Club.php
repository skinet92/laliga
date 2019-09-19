<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * Club
 *
 * @ORM\Table(name="club")
 * @UniqueEntity("nombre", message = "Ya existe un club con este nombre.");
 * @ORM\Entity
 */
class Club
{
    /**
     * @var integer
     *
     * @ORM\Column(name="idclub", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="club_idclub_seq", allocationSize=1, initialValue=1)
     */
    private $idclub;

    /**
     * @var string
     * @Assert\NotNull(message="Este campo no puede estar vacio")
     * @Assert\Regex(pattern="/^[a-zA-Z áéíóúñAÉÍÓÚÑ]+$/", message = "Este campo permite solo letras.")
     * @ORM\Column(name="nombre", type="string", length=50, nullable=true)
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="escudo", type="string", length=100, nullable=true)
     */
    private $escudo;

    /**
     * @var float
     * @Assert\NotNull(message="Este campo no puede estar vacio")
     * @Assert\Regex(pattern="/^[0-9.]+$/", message = "Este campo permite solo números mayores o igual a 0.")
     * @ORM\Column(name="presupuesto", type="float", precision=10, scale=0, nullable=true)
     */
    private $presupuesto;



    /**
     * Get idclub
     *
     * @return integer
     */
    public function getIdclub()
    {
        return $this->idclub;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return Club
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

    /**
     * Set escudo
     *
     * @param string $escudo
     *
     * @return Club
     */
    public function setEscudo($escudo)
    {
        $this->escudo = $escudo;

        return $this;
    }

    /**
     * Get escudo
     *
     * @return string
     */
    public function getEscudo()
    {
        return $this->escudo;
    }

    /**
     * Set presupuesto
     *
     * @param float $presupuesto
     *
     * @return Club
     */
    public function setPresupuesto($presupuesto)
    {
        $this->presupuesto = $presupuesto;

        return $this;
    }

    /**
     * Get presupuesto
     *
     * @return float
     */
    public function getPresupuesto()
    {
        return $this->presupuesto;
    }

    public function __toString()
    {
        return $this->getNombre();
    }
}
