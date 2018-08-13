<?php

namespace CorreoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use CorreoBundle\Repository\PersonaRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class DestinatarioType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('lista',ChoiceType::class, array (
                                    "label" => 'Detinatario',
                                    'required' => false,
                                    'mapped' => false,
                                    'placeholder' => 'Seleccione Lista de Distribución. ..',
                                    'choices'  => ['Masivo Activos' => 1,
                                                   'Individual' => 2],
                                    "attr" => array ("class" => "form-control")))
                ->add('persona',EntityType::class, array (
                                    "label" => 'Persona',
                                    'class' => 'CorreoBundle:Persona',
                                    'required' => false,
                                    'placeholder' => 'Seleccione Destinatario.... ',
                                    'query_builder' => function (PersonaRepository $er) {
											return $er->createQueryBuilder('u')
                                            ->andwhere("u.email is not null and u.email != ''")
											->orderBy('u.nombre', 'ASC');
                                    },
                                    "attr" => array ("class" => "form-control")))
                                            
                ->add('Guardar', SubmitType::class, array(
                                    "label" => " Añadir ",
                                    "attr" => array("class" => "form-submit btn btn-success")))
                ;
    }
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CorreoBundle\Entity\Destinatario'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'correobundle_destinatario';
    }


}
