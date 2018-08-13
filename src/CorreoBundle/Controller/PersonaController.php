<?php

namespace CorreoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use CorreoBundle\Entity\Persona;
use CorreoBundle\Form\PersonaType;
use Symfony\Component\HttpFoundation\Session\Session;

class PersonaController extends Controller
{
    private $sesion;
    
    public function __construct(){
        $this->sesion = new Session();
    }
    
    public function QueryAction() {
        $EM = $this->getDoctrine()->getManager();
        $Persona_repo = $EM->getRepository("CorreoBundle:Persona");
        $Personas = $Persona_repo->findAll();
        $params = ["Personas" => $Personas];
        return $this->render ('CorreoBundle:Persona:query.html.twig', $params);
    }
    public function AddAction(Request $request) {
        $EM = $this->getDoctrine()->getManager();
        $Persona_repo = $EM->getRepository("CorreoBundle:Persona");
        $Persona = new Persona();
        $PersonaForm = $this->createForm(PersonaType::class,$Persona);
        $PersonaForm->handleRequest($request);
        
        if ($PersonaForm->isSubmitted()){
            $Persona = new Persona();
            $Persona->setNombre($PersonaForm->get('nombre')->getData());
            $Persona->setEmail($PersonaForm->get('email')->getData());
            $file=$PersonaForm["fichero"]->getData();
//            dump($file);
            if(!empty($file) && $file!=null){
                $ext=$file->guessExtension();
                $file_name= $file->getClientOriginalName();
                $file->move("f",$file_name);
                $Persona->setFichero($file_name);
            }
            $EM->persist($Persona);
            $EM->flush();
            $params = [];
            return $this->redirectToRoute("queryPersona",$params);
        }
        
        $params = ["form" =>$PersonaForm->createView()
                   ];
        return $this->render("CorreoBundle:Persona:add.html.twig", $params);
    }

    public function EditAction(Request $request, $id) {
        $EM = $this->getDoctrine()->getManager();
        $Persona_repo = $EM->getRepository("CorreoBundle:Persona");
        $Persona = $Persona_repo->find($id);
        $PersonaForm = $this->createForm(PersonaType::class,$Persona);
        $PersonaForm->handleRequest($request);
        
        if ($PersonaForm->isSubmitted()){
            $Persona->setNombre($PersonaForm->get('nombre')->getData());
            $Persona->setEmail($PersonaForm->get('email')->getData());
            $file=$PersonaForm["fichero"]->getData();
            if(!empty($file) && $file!=null){
                $ext=$file->guessExtension();
                $file_name= $file->getClientOriginalName();
                $file->move("f",$file_name);
                $Persona->setFichero($file_name);
            }
            $EM->persist($Persona);
            $EM->flush();
            $params = [];
            return $this->redirectToRoute("queryPersona",$params);
        }
        $params = ["form" =>$PersonaForm->createView(),
                   "Persona" => $Persona];
        
        return $this->render("CorreoBundle:Persona:add.html.twig", $params);
    }
    
    public function DeleteAction($id) {
        $EM = $this->getDoctrine()->getManager();
        $Persona_repo = $EM->getRepository("CorreoBundle:Persona");
        $Persona = $Persona_repo->find($id);
        $EM->remove($Persona);
        $EM->flush();
        
        $status = 'PERSONA ELIMINADA CORRECTAMENTE';
        $this->sesion->getFlashBag()->add("status",$status);
        return $this->redirectToRoute("queryPersona");
    }
}
