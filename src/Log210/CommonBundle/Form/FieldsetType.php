<?php
namespace Log210\CommonBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;

class FieldsetType extends AbstractType
{
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'legend' => null,
            'compound' => true,
            'mapped' => false,
            'label' => false,
            'required' => false,
            'inherit_data' => true
        ]);
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['legend'] = $options['legend'];
    }

    public function getName()
    {
        return 'fieldset';
    }
}