<?php

namespace CertBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Response;
use PHPMailer\PHPMailer\PHPMailer;
use CertBundle\Entity\LogDescarga;
use Symfony\Component\HttpFoundation\Request;
use CertBundle\Form\ImportarType;
use PhpOffice\PhpSpreadsheet\IOFactory;

class CertificadoController extends Controller {

    private $sesion;

    public function __construct() {
        $this->sesion = new Session();
    }

    public function envioAction($usuario_id) {
        $em = $this->getDoctrine()->getManager();
        $Usuario_repo = $em->getRepository("CertBundle:Usuario");
        $Fichero_repo = $em->getRepository("CertBundle:Fichero");
        $Usuario = $Usuario_repo->find($usuario_id);
        $Fichero = $Fichero_repo->queryByUsuario($usuario_id);

        if ($Fichero) {
            $mail = new PHPMailer();
            $mail->IsHTML(true);
            $mail->IsSMTP();
            $mail->AddAddress($Usuario->getEmail());
            $mail->SMTPAuth = false;
            $mail->Port = 25;
            $mail->Host = 'mail.madrid.org';
            $mail->Username = "jano@salud.madrid.org.com";
            $mail->Password = 'Vado_1965';
            $mail->From = 'dtrh@salud.madrid.org.com';
            $mail->FromName = 'PRUEBAS DE JOSE LUIS';
            $mail->CharSet = 'UTF-8';
            $mail->Subject = 'CERTIFICADO DE SERVICIOS CONCURSO DE MOVILIDAD';
            $mail->Body = 'CERTIFICADO DE SERVICIOS CONCURSO DE MOVILIDAD';

            $rootDir = $this->get('kernel')->getRootDir();
            $path = $rootDir . '/../web/certificados/';

            $ad = $path . $Fichero["fichero"];
            $mail->addAttachment($ad);
            $enviado = $mail->Send();
            if ($enviado) {
                $status = 'CERTIFICADO ENVIADO CORRECTAMENTE';
            } else {
                $status = 'ERROR EN ENVIO DE CERTIFICADO';
            }
        } else {
            $status = 'ERROR NO EXISTE CERTIFICADO PARA ESTE DNI:'
                    . $Usuario->getDni()
                    . ' '
                    . $Usuario->getNombre();
        }

        $this->sesion->getFlashBag()->add("status", $status);
        return $this->render('CertBundle:Default:enviado.html.twig');
    }

    public function descargaAction($usuario_id) {
        $em = $this->getDoctrine()->getManager();
        $Usuario_repo = $em->getRepository("CertBundle:Usuario");
        $Fichero_repo = $em->getRepository("CertBundle:Fichero");
        $Usuario = $Usuario_repo->find($usuario_id);
        $Fichero = $Fichero_repo->queryByUsuario($usuario_id);
        if (!$Fichero) {
            $status = 'ERROR NO EXISTE CERTIFICADO PARA ESTE DNI:'
                    . $Usuario->getDni()
                    . ' '
                    . $Usuario->getNombre();
            $this->sesion->getFlashBag()->add("status", $status);
            return $this->render('CertBundle:Default:error.html.twig');
        }

        $rootDir = $this->get('kernel')->getRootDir();
        $path = $rootDir . '/../web/certificados/';
        $fichero = $Fichero["fichero"];
        $ad = $path . $fichero;

        if (file_exists($ad)) {
            $Fichero_ob = $Fichero_repo->find($Fichero['id']);
            $this->creaLog($Usuario, $Fichero_ob);
            $mail = new PHPMailer();
            $mail->IsHTML(true);
            $mail->IsSMTP();
            $mail->AddAddress($Usuario->getEmail());
            $mail->SMTPAuth = false;
            $mail->Port = 25;
            $mail->Host = 'mail.madrid.org';
            $mail->Username = "jano@salud.madrid.org.com";
            $mail->Password = 'Vado_1965';
            $mail->From = 'carrera.profesional@salud.madrid.org';
            $mail->FromName = 'Dirección Técnica de RR.HH. de Atención Primaria';
            $mail->CharSet = 'UTF-8';
            $mail->Subject = 'Descarga del Certificado de Servicios Prestados en Atención Primaria';
            $mail->Body = 'Estimado/a compañero/a: <br/><br/>'
                    . ' Se acaba de descargar el Certificado de Servicios Prestados en Atención Primaria del '
                    . ' SERMAS, que se ha elaborado de oficio por esta Administración y ha servido de base '
                    . ' para acreditar el tiempo de permanencia en servicio activo en la Categoría para la que '
                    . ' solicita el reconocimiento de la Carrera Profesional<br/><br/>'
                    . ' Para el reconocimiento del Nivel dentro de la Carrera Profesional, aparte de este certificado, '
                    . ' se tendrán en cuenta los periodos trabajados en otras instituciones de los que haya aportado certificación, '
                    . ' así como el resto de los factores de evaluación descritos en el Anexo III. <br/><br/>'
                    . ' Un cordial saludo. <br/><br/>'
                    . ' <strong>Carrera Profesional</strong> <br/>'
                    . ' Dirección Técnica de RRHH de Atención Primaria<br/>'
                    . ' C/ San Martín de Porres 6, Planta 4ª <br/>'
                    . ' 28035 Madrid <br/>'
                    . ' e-mail: movilidad.interna.ap@salud.madrid.org';
            $mail->Send();

            $content = file_get_contents($ad);
            $response = new Response();
            $response->headers->set('Content-type', 'application/pdf');
            $response->headers->set('Content-Disposition', 'attachment;filename="' . $fichero);
            $response->setContent($content);
            return $response;
        } else {
            $status = 'ERROR NO EXISTE CERTIFICADO PARA ESTE DNI:'
                    . $Usuario->getDni()
                    . ' '
                    . $Usuario->getNombre();
            $this->sesion->getFlashBag()->add("status", $status);
            return $this->render('CertBundle:Default:error.html.twig');
        }
    }

    public function creaLog($Usuario, $Fichero) {
        $em = $this->getDoctrine()->getManager();
        $LogDescarga = new LogDescarga();
        $LogDescarga->setFcDescarga(date("d-m-Y H:i:s"));
        $LogDescarga->setUsuario($Usuario);
        $LogDescarga->setFichero($Fichero);
        $LogDescarga->setIp($this->sesion->get('ip'));
        $em->persist($LogDescarga);
        $em->flush();
        return;
    }

//    public function envioCorreccionAction() {
//        $em = $this->getDoctrine()->getManager();
//
//        $Correo_repo = $em->getRepository("CertBundle:Correo");
//        $Correos = $Correo_repo->findAll();
//        foreach ($Correos as $Correo) {
//            $this->enviaMailCorreccion($Correo->getUsuario()->getEmail());
//        }
//        return $this->render('CertBundle:Default:enviadaCorreccion.html.twig');
//    }
//    public function enviaMailCorreccion($email) {
//        $mail = new PHPMailer();
//        $mail->IsHTML(true);
//        $mail->IsSMTP();
//        $mail->AddAddress($email);
//        $mail->SMTPAuth = false;
//        $mail->Port = 25;
//        $mail->Host = 'mail.madrid.org';
//        $mail->Username = "jano@salud.madrid.org.com";
//        $mail->Password = 'Vado_1965';
//        $mail->From = 'movilidad.interna.ap@salud.madrid.org';
//        $mail->FromName = 'Dirección Técnica de RR.HH. de Atención Primaria';
//        $mail->CharSet = 'UTF-8';
//        $mail->Subject = 'Correción del Certificado de Servicios Prestados en Atención Primaria';
//        $mail->Body = ' Estimado/a Compañero/a: <br/><br/>'
//                . ' Habiendose detectado un error, <strong><u>que no afecta al baremo</u></strong>, '
//                . ' en la confección del certificado emitido para el proceso de movilidad interna'
//                . ' en A.P., te comunicamos que tienes la posibilidad de volver a consultar el nuevo '
//                . ' certificado corregido. <br/><br/>'
//                . ' Este nuevo certificado no corrige las posibles reclamaciones de nuevos periodos y/o'
//                . ' modificación de apartados <br/><br/>'
//                . ' Atentamente, <br/>'
//                . ' <strong>Movilidad Interna</strong> <br/>'
//                . ' Dirección Técnica de RRHH de Atención Primaria<br/>'
//                . ' C/ San Martín de Porres 6, Planta 4ª <br/>'
//                . ' 28035 Madrid <br/>'
//                . ' e-mail: movilidad.interna.ap@salud.madrid.org';
//
//        $enviado = $mail->Send();
//        return $enviado;
//    }

    public function cargaFicheroAction(Request $request) {
        $ImportarForm = $this->createForm(ImportarType::class);
        $ImportarForm->handleRequest($request);
        $em = $this->getDoctrine()->getManager();

        if ($ImportarForm->isSubmitted()) {
            $file = $ImportarForm["fichero"]->getData();
            if (!empty($file) && $file != null) {
                $file_name = $file->getClientOriginalName();
                $file->move("upload", $file_name);
                $PHPExcel = $this->validarFichero($file);
                if ($PHPExcel == null) {
                    $status = "***ERROR EN FORMATO FICHERO **: " . $file_name;
                    $this->sesion->getFlashBag()->add("status", $status);
                    $params = ["form" => $ImportarForm->createView()];
                    return $this->render("CertBundle:Default:importar.html.twig", $params);
                }

                $this->carga($PHPExcel);

                $params = array();
                return $this->render("CertBundle:Default:finCarga.html.twig", $params);
            }
        }

        $params = ["form" => $ImportarForm->createView()];
        return $this->render("CertBundle:Default:importar.html.twig", $params);
    }

    public function validarFichero($fichero) {
        $Cabecera = array("A" => "DNI",
            "B" => "FICHERO",
            "C" => "NOMBRE");

        $file = "upload/" . $fichero->getClientOriginalName();
        $PHPExcel = \PhpOffice\PhpSpreadsheet\IOFactory::load($file);
        $objWorksheet = $PHPExcel->setActiveSheetIndex(0);
        $headingsArray = $objWorksheet->rangeToArray('A1:C1', null, true, true, true);
        $linea = $headingsArray[1];

        if ($linea != $Cabecera) {
            return null;
        }

        return $PHPExcel;
    }

    public function carga($PHPExcel) {
        $em = $this->getDoctrine()->getManager();
        $objWorksheet = $PHPExcel->setActiveSheetIndex(0);
        $highestRow = $objWorksheet->getHighestRow();
        $highestColumn = $objWorksheet->getHighestColumn();
        $Resultadocarga = array();
        $col = 0;

        for ($i = 2; $i <= $highestRow; $i++) {
            $em = $this->getDoctrine()->getManager();
            if (!$em->isOpen()) {
                $em = $this->getDoctrine()->getManager()->create($em->getConnection(), $em->getConfiguration());
            }
            $headingsArray = array();
            $headingsArray = $objWorksheet->rangeToArray('A' . $i . ':C' . $i, null, true, true, true);
            $headingsArray = $headingsArray[$i];

            $dni = $headingsArray["A"];
            $fichero = $headingsArray["B"];
            $nombre = $headingsArray["C"];

            $Fichero = new \CertBundle\Entity\Fichero();
            $Fichero->setDni($dni);
            $Fichero->setFichero($fichero);
            $Fichero->setNombre($nombre);

            try {
                $em->persist($Fichero);
                $em->flush();
            } catch (\Doctrine\DBAL\Exception\UniqueConstraintViolationException $ex) {
                $status = "**DNI: " . $dni . " ya tiene un certificado, no se carga";
                $this->sesion->getFlashBag()->add("status", $status);
            }
        }
        return true;
    }

}
