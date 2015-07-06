<?php

namespace Log210\LivraisonBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class MenuType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('restaurant', 'reference', array('type'=>'Log210LivraisonBundle:restaurant'))
            ->add($builder->create('platsFields', 'fieldset', ['legend' => 'Plats'])
                ->add('plats', 'bootstrap_collection', [
                    'label' => false,
                    'type' => new PlatType,
                    'allow_add' => true,
                    'allow_delete' => true,
                    'by_reference' => false,
                    'add_button_text'    => 'newPlat',
                    'delete_button_text' => '',
                    'prototype' => true,
                    'prototype_name' => 'tag__name__',
                    'options' => [
                        'label' => false
                    ]
                ])
            )
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Log210\LivraisonBundle\Entity\Menu'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'log210_livraisonbundle_menu';
    }
}
