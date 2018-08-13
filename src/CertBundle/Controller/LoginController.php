<?php

namespace CertBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

use CertBundle\Entity\Acceso;
use CertBundle\Entity\Usuario;

class LoginController extends Controller
{
    private $sesion;
    
    public function __construct(){
        $this->sesion = new Session();
    }
    
    public function logoutAction() {
        session_destroy();
        return $this->redirectToRoute("cert_homepage");
    }

    public function loginAction()
    {
        $navegador = @get_browser(null, false);
        if ( ($navegador != null) && ($navegador->browser == "IE" && $navegador->version < 12)) {
            return $this->render('CertBundle:IE8:login8.html.twig');
        } else { 
            return $this->render('CertBundle:Login:login.html.twig');
        }
    }  
    
    public function checkAction(Request $request){
        $username = $_POST["usuario"];
        $password = $_POST["password"];
        $Usuario_xx = $this->usuarioAutenticadoLdap($username,$password);
        
        if ($Usuario_xx) {
            $this->sesion->set('username',$username);
            $this->sesion->set('nombre',$Usuario_xx['displayname'][0]);
            $this->sesion->set('email',$Usuario_xx['mail'][0]);
            $ultAcceso = $this->LogAcceso($username);
            $this->sesion->set('usuario_id',$ultAcceso['id']);
            $this->sesion->set('fecha',$ultAcceso['fecha']);
            $this->sesion->set('ip',$_SERVER['REMOTE_ADDR']); 
            $navegador = @get_browser(null, false);
            if ( ($navegador != null) && ($navegador->browser == "IE" && $navegador->version < 12)) {
                return $this->render('CertBundle:IE8:acceso8.html.twig');
            } else { 
                return $this->render('CertBundle:Default:acceso.html.twig');
            }
        } else { 
            $status = " error en accceso ";
            $this->sesion->getFlashBag()->add("status",$status);
            return $this->redirectToRoute("login");
        }
    }
     
    public function usuarioAutenticadoLdap($username,$password) {
        $autenticado = false;
        $ldaprdn  = $username;
        $ldappass = $password;
        //$attributes_ad = array("displayName","description","cn","givenName","sn","mail","co","mobile","company","displayName");
        $attributes_ad = array("displayName","mail");
        $servidor = "salud.madrid.org";
        $dn = "OU=CSCM,DC=salud,DC=madrid,DC=org";
        $ldapconn =ldap_connect($servidor)
        or die("No se puede conectar con el servidor LDAP.");
        ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
        ldap_set_option($ldapconn, LDAP_OPT_REFERRALS, 0);
        if ($ldapconn && trim($ldappass) != ""){
            $autenticado=@ldap_bind($ldapconn, $ldaprdn . "@" . $servidor, $ldappass);
            if ($autenticado) {
                $result=ldap_search($ldapconn, $dn, "(samaccountname=$username)",$attributes_ad);
                $entries=ldap_get_entries($ldapconn,$result);
                $Usuario = $entries[0];
                return $Usuario;
            }
        }

        return $autenticado;
    }
    
    public function LogAcceso($username) {
        $em = $this->getDoctrine()->getManager();
        $Usuario_repo = $em->getRepository("CertBundle:Usuario");
        $Acceso_repo = $em->getRepository("CertBundle:Acceso");
        
        $id = $Usuario_repo->queryByUsername($username);
        if ( $id) {
            $Usuario = $Usuario_repo->find($id);
            $ultAcceso["fecha"] = $Acceso_repo->ultimoAcceso($Usuario->getId());
            $ultAcceso["id"]=$Usuario->getId();
        } else  {
            $Usuario = new Usuario();
            $Usuario->setDni($this->sesion->get('username'));
            $Usuario->setNombre($this->sesion->get('nombre'));
            $Usuario->setEmail($this->sesion->get('email'));
            $em->persist($Usuario);
            $em->flush();
            $ultAcceso['id']= $Usuario->getId();
            $ultAcceso['fecha']= date("d-m-Y H:i:s");
        }
        
        $Acceso = new Acceso();
        $fcacceso = date("d-m-Y H:i:s");
        $Acceso->setUsuario($Usuario);
        $Acceso->setFcacceso($fcacceso);
        $em->persist($Acceso);
        $em->flush();
        
        return $ultAcceso;
    }

}
