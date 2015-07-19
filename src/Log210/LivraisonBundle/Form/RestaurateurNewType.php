<?php

namespace Log210\LivraisonBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RestaurateurNewType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username')
            ->add('plainPassword', 'password', array('label'=>'Password'))
            ->add('email', 'email')
            ->add('name')
            ->add('description')
            ->add('restaurants')
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Log210\LivraisonBundle\Entity\Restaurateur'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'log210_livraisonbundle_restaurateur';
    }
}
