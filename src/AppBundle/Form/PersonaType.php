<?php

namespace AppBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class PersonaType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre')
            ->add('dni')
            ->add('fechanacimiento', DateType::class, array('widget' => 'single_text', 'format' => 'dd/MM/yyyy'))
            ->add('email')
            ->add('salario')
            ->add('perfil',EntityType::class, array(
                'class' => 'AppBundle\Entity\Perfil',
                'placeholder' => '--Selecciona--',
                'empty_data' => null,
            ))
            ->add('club',EntityType::class, array(
                'class' => 'AppBundle\Entity\Club',
                'placeholder' => '--Selecciona--',
                'empty_data' => null,
            ))
            ->add('posicion',EntityType::class, array(
                'class' => 'AppBundle\Entity\Posicionjugador',
                'placeholder' => '--Selecciona--',
                'empty_data' => null,
            ))
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Persona'
        ));
    }
}
