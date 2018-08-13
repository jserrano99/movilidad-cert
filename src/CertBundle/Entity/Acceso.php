<?php

namespace CertBundle\Entity;

/**
 * Acceso
 */
class Acceso
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var \string
     */
    private $fcacceso;

    /**
     * @var \CertBundle\Entity\Usuario
     */
    private $usuario;
    
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
     * Set fcacceso
     *
     * @param \DateTime $fcacceso
     *
     * @return Acceso
     */
    public function setFcacceso($fcacceso)
    {
        $this->fcacceso = $fcacceso;

        return $this;
    }

    /**
     * Get fcacceso
     *
     * @return \DateTime
     */
    public function getFcacceso()
    {
        return $this->fcacceso;
    }
    
    /**
     * Set usuario
     *
     * @param \CertBundle\Entity\Usuario $usuario
     *
     * @return Acceso
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
}

