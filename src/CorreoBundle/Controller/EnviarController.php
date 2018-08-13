<?php

namespace CorreoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use CorreoBundle\Entity\Adjunto;
use CorreoBundle\Form\AdjuntoType;
use Symfony\Component\HttpFoundation\Session\Session;
use PHPMailer\PHPMailer\PHPMailer;

class EnviarController extends Controller
{
    private $sesion;
    
    public function __construct(){
        $this->sesion = new Session();
        
        
    }
    public function indexAction()
    {
        return $this->render('CorreoBundle::index.html.twig');
    }
    
    public function AddAction($id) {
        $EM = $this->getDoctrine()->getManager();
        $Correo_repo = $EM->getRepository("CorreoBundle:Correo");
        $Adjunto_repo = $EM->getRepository("CorreoBundle:Adjunto");
        $Destinatario_repo = $EM->getRepository("CorreoBundle:Destinatario");
        $Correo = $Correo_repo->find($id);
        $Adjuntos = $Adjunto_repo->queryByCorreo($id);

        $Destinatarios = $Destinatario_repo->queryByCorreo($id);
        foreach ($Destinatarios as $Destinatario) {
            $mail = new PHPMailer();
            $mail->IsHTML(true);
            $mail->IsSMTP();
            $mail->AddAddress($Destinatario['email']);
            $mail->SMTPAuth = false;
//            $mail->SMTPSecure = "TLS";
//            $mail->Port = 587;
            $mail->Port = 25;
            $mail->Host = 'mail.madrid.org';
            $mail->Username   = "jano@salud.madrid.org.com";
            $mail->Password = 'Vado_1965';
            $mail->From = 'jano@salud.madrid.org.com';
            $mail->FromName = 'PRUEBAS DE JOSE LUIS';
            $mail->CharSet = 'UTF-8';
            $mail->Subject = $Correo->getAsunto();
            $mail->Body = $Correo->getCuerpo();
            $path = '/usr/aplic_ICM/prod/web/jano/pasoprodV2/web/';
            $path2 = $path.'certificados/';
            $path1 = $path.'adjuntos/';
            $ad = $path1.$Destinatario["fichero"];
            $mail->addAttachment($ad);
            foreach ($Adjuntos as $Adjunto) { 
                $ad = $path2.$Adjunto["fichero"];
                $mail->addAttachment($ad);
            }
            $enviado = $mail->Send();
        }
        $status = 'CORREO ENVIADO CORRECTAMENTE';
        $this->sesion->getFlashBag()->add("status",$status);
       
        return $this->redirectToRoute("queryCorreo");;
    }

}
