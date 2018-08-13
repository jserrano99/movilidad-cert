<?php

namespace CorreoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class PersonaType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('nombre', TextType::class, array (
                                    "label" => 'Nombre',
                                    "required" => true,
                                    "attr" => array ("class" => "form-control")))
                ->add('email', TextType::class, array (
                                    "label" => 'Correo Electrónico',
                                    "required" => true,
                                    "attr" => array ("class" => "form-control")))
                ->add('fichero', FileType::class, array (
                                    "label" => 'Fichero',
                                    "mapped" => false,
                                    "required" => false,
                                    "attr" => array ("class" => "form-control")))
                
                ->add('Guardar', SubmitType::class, array(
                                    "attr" => array("class" => "form-submit btn btn-success")))
                ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CorreoBundle\Entity\Persona'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'correobundle_persona';
    }


}
