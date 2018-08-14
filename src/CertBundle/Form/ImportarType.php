<?php

namespace CertBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class ImportarType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('fichero', FileType::class, array(
                    "label" => 'Fichero a Cargar',
                    "mapped" => false,
                    "required" => false,
                    "attr" => array("class" => "form-control")))
                ->add('Guardar', SubmitType::class, array(
                    "attr" => array("class" => "btn btn-t btn-success")))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'importar';
    }

}
