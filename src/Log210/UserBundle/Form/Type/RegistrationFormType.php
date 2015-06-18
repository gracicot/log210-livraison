<?php

namespace Log210\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;


class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('adress');
    }

    public function getParent()
    {
        return 'fos_user_registration';
    }
    public function getName()
    {
        return 'log210_user_registration';
    }
}
