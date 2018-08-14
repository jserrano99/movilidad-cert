<?php

namespace CertBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller {

    public function indexAction() {
        return $this->render('CertBundle:Default:index.html.twig');
    }

}
