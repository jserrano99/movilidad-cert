<?php

namespace CertBundle\Entity;

/**
 * Acceso
 */
class Correo
{
    /**
     * @var int
     */
    private $id;

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

