<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * Persona
 *
 * @ORM\Table(name="persona", indexes={@ORM\Index(name="Ref42", columns={"posicion"}), @ORM\Index(name="Ref31", columns={"perfil"}), @ORM\Index(name="Ref19", columns={"club"})})
 * @UniqueEntity("dni", message = "Ya existe esta persona.");
 * @ORM\Entity
 */
class Persona
{
    /**
     * @var integer
     *
     * @ORM\Column(name="idpersona", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="persona_idpersona_seq", allocationSize=1, initialValue=1)
     */
    private $idpersona;

    /**
     * @var string
     * @Assert\NotNull(message="Este campo no puede estar vacio")
     * @Assert\Regex(pattern="/^[a-zA-Z áéíóúñAÉÍÓÚÑ]+$/", message = "Este campo permite solo letras.")
     * @ORM\Column(name="nombre", type="string", length=50, nullable=true)
     */
    private $nombre;

    /**
     * @var string
     * @Assert\NotNull(message="Este campo no puede estar vacio")
     * @ORM\Column(name="dni", type="string", length=9, nullable=true)
     */
    private $dni;

    /**
     * @var \DateTime
     * @Assert\NotNull(message="Este campo no puede estar vacio")
     * @ORM\Column(name="fechanacimiento", type="date", nullable=true)
     */
    private $fechanacimiento;

    /**
     * @var string
     * @Assert\NotNull(message="Este campo no puede estar vacio")
     * @Assert\Email(
     *     message = "El email '{{ value }}' no es un email válido.",
     *     )
     * @ORM\Column(name="email", type="string", length=100, nullable=true)
     */
    private $email;

    /**
     * @var float
     * @Assert\Regex(pattern="/^[0-9.]+$/", message = "Este campo permite solo números mayores o igual a 0.")
     * @ORM\Column(name="salario", type="float", precision=10, scale=0, nullable=true)
     */
    private $salario;

    /**
     * @var \Perfil
     * @Assert\NotNull(message="Este campo no puede estar vacio")
     * @ORM\ManyToOne(targetEntity="Perfil")
     * @ORM\JoinColumns({
     * @ORM\JoinColumn(name="perfil", referencedColumnName="idperfil")
     * })
     */
    private $perfil;

    /**
     * @var \Posicionjugador
     * @ORM\ManyToOne(targetEntity="Posicionjugador")
     * @ORM\JoinColumns({
     * @ORM\JoinColumn(name="posicion", referencedColumnName="idposicion")
     * })
     */
    private $posicion;

    /**
     * @var \Club
     * @ORM\ManyToOne(targetEntity="Club")
     * @ORM\JoinColumns({
     * @ORM\JoinColumn(name="club", referencedColumnName="idclub")
     * })
     */
    private $club;



    /**
     * Get idpersona
     *
     * @return integer
     */
    public function getIdpersona()
    {
        return $this->idpersona;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return Persona
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
     * Set dni
     *
     * @param string $dni
     *
     * @return Persona
     */
    public function setDni($dni)
    {
        $this->dni = $dni;

        return $this;
    }

    /**
     * Get dni
     *
     * @return string
     */
    public function getDni()
    {
        return $this->dni;
    }

    /**
     * Set fechanacimiento
     *
     * @param \DateTime $fechanacimiento
     *
     * @return Persona
     */
    public function setFechanacimiento($fechanacimiento)
    {
        $this->fechanacimiento = $fechanacimiento;

        return $this;
    }

    /**
     * Get fechanacimiento
     *
     * @return \DateTime
     */
    public function getFechanacimiento()
    {
        return $this->fechanacimiento;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Persona
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set salario
     *
     * @param float $salario
     *
     * @return Persona
     */
    public function setSalario($salario)
    {
        $this->salario = $salario;

        return $this;
    }

    /**
     * Get salario
     *
     * @return float
     */
    public function getSalario()
    {
        return $this->salario;
    }

    /**
     * Set perfil
     *
     * @param \AppBundle\Entity\Perfil $perfil
     *
     * @return Persona
     */
    public function setPerfil(\AppBundle\Entity\Perfil $perfil = null)
    {
        $this->perfil = $perfil;

        return $this;
    }

    /**
     * Get perfil
     *
     * @return \AppBundle\Entity\Perfil
     */
    public function getPerfil()
    {
        return $this->perfil;
    }

    /**
     * Set posicion
     *
     * @param \AppBundle\Entity\Posicionjugador $posicion
     *
     * @return Persona
     */
    public function setPosicion(\AppBundle\Entity\Posicionjugador $posicion = null)
    {
        $this->posicion = $posicion;

        return $this;
    }

    /**
     * Get posicion
     *
     * @return \AppBundle\Entity\Posicionjugador
     */
    public function getPosicion()
    {
        return $this->posicion;
    }

    /**
     * Set club
     *
     * @param \AppBundle\Entity\Club $club
     *
     * @return Persona
     */
    public function setClub(\AppBundle\Entity\Club $club = null)
    {
        $this->club = $club;

        return $this;
    }

    /**
     * Get club
     *
     * @return \AppBundle\Entity\Club
     */
    public function getClub()
    {
        return $this->club;
    }

    public function __toString()
    {
        return $this->getNombre();
    }
}
