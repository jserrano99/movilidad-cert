<?php

namespace CertBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $navegador = @get_browser(null, false);
        if ( ($navegador != null) && ($navegador->browser == "IE" && $navegador->version < 12)) {
            return $this->render('CertBundle:IE8:index8.html.twig');
        } else { 
            return $this->render('CertBundle:Default:index.html.twig');
        }
    }
}
