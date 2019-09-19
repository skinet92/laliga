<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Posicionjugador
 *
 * @ORM\Table(name="posicionjugador")
 * @ORM\Entity
 */
class Posicionjugador
{
    /**
     * @var integer
     *
     * @ORM\Column(name="idposicion", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="posicionjugador_idposicion_seq", allocationSize=1, initialValue=1)
     */
    private $idposicion;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=50, nullable=true)
     */
    private $nombre;



    /**
     * Get idposicion
     *
     * @return integer
     */
    public function getIdposicion()
    {
        return $this->idposicion;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return Posicionjugador
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
