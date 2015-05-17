<?php

namespace Log210\LivraisonBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RestaurantType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            $builder->create('nameSet', 'fieldset')
                ->add('name')
                ->add('description')
                ->add('adresse')
                ->add('restaurateur')
            )
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Log210\LivraisonBundle\Entity\Restaurant'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'log210_livraisonbundle_restaurant';
    }
}
