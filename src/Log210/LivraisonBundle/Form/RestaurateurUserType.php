<?php

namespace Log210\LivraisonBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use FOS\UserBundle\Form\Type\RegistrationFormType;

class RestaurateurUserType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('user', new RegistrationFormType('Log210\UserBundle\Entity\User'), ['label' => false])
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'Log210\LivraisonBundle\Entity\Restaurateur'
        ]);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'log210_livraisonbundle_restaurateur_user';
    }
}
