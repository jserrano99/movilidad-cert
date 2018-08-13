<?php

namespace CertBundle\Repository;

/**
 * UsuarioRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UsuarioRepository extends \Doctrine\ORM\EntityRepository
{
    public function  queryByUsername($username){
        $em = $this->getEntityManager();
		$db = $em->getConnection();
        $query = " select id from cert_usuario where dni = :username";
        $stmt = $db->prepare($query);
		$params = [":username" => $username];
		$stmt->execute($params);
		$po = $stmt->fetch();
        if ($po){
            return $po['id'];
        } else {
            return false;
        }
    }
}