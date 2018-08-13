<?php

namespace CertBundle\Entity;

/**
 * LogDescarga
 */
class LogDescarga
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $fcdescarga;

    /**
     * @var string
     */
    private $equipo;

    /**
     * @var string
     */
    private $ip;

    /**
     * @var /CertBundle/Entity/Usuario
     */
    private $usuario;

    /**
     * @var /CertBundle/Entity/Fichero
     */
    private $fichero;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set fcDescarga
     *
     * @param string $fcDescarga
     *
     * @return LogDescarga
     */
    public function setFcdescarga($fcdescarga)
    {
        $this->fcdescarga = $fcdescarga;

        return $this;
    }

    /**
     * Get fcDescarga
     *
     * @return string
     */
    public function getFcdescarga()
    {
        return $this->fcdescarga;
    }

    /**
     * Set equipo
     *
     * @param string $equipo
     *
     * @return LogDescarga
     */
    public function setEquipo($equipo)
    {
        $this->equipo = $equipo;

        return $this;
    }

    /**
     * Get equipo
     *
     * @return string
     */
    public function getEquipo()
    {
        return $this->equipo;
    }

    /**
     * Set ip
     *
     * @param string $ip
     *
     * @return LogDescarga
     */
    public function setIp($ip)
    {
        $this->ip = $ip;

        return $this;
    }

    /**
     * Get ip
     *
     * @return string
     */
    public function getIp()
    {
        return $this->ip;
    }
    
    /**
     * Set usuario
     *
     * @param \CertBundle\Entity\Usuario $usuario
     * 
     * #return LogDescarga
     */
    public function setUsuario(\CertBundle\Entity\Usuario $usuario=null)
    {
        $this->usuario = $usuario;

        return $this;
    }

    /**
     * Get usuario
     *
     * @return \CertBundle\Entity\Usuario
     */
    public function getUsuario()
    {
        return $this->usuario;
    }
    
    /**
     * Set fichero
     *
     * @param \CertBundle\Entity\Fichero $fichero
     *
     * @return LogDescarga
     */
    public function setFichero(\CertBundle\Entity\Fichero $fichero=null)
    {
        $this->fichero = $fichero;

        return $this;
    }

    /**
     * Get fichero
     *
     * @return \CertBundle\Entity\Fichero
     */
    public function getFichero()
    {
        return $this->fichero;
    }
}

