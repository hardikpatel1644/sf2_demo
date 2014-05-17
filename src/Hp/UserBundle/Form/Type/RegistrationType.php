<?php

namespace Hp\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Description of RegistrationType
 *
 * @author Hardik Patel <hardikpatel1644@gmail.com>
 */
class RegistrationType extends AbstractType 
{
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('user', new UserType());
        $builder->add(
                'terms', 'checkbox', array('property_path' => 'termsAccepted')
        );
        $builder->add('Register', 'submit');
    }

    public function getName() {
        return 'registration';
    }

}
