<?php

namespace Hp\CategoryBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Description of CategoryType
 *
 * @author Hardik Patel <hardikpatel1644@gmail.com>
 */
class CategoryType extends AbstractType {

    protected $asCategories;

    public function __construct($asCategories) {


        $this->asCategories = $this->getAllCategories($asCategories);
        ;
    }

    public function buildForm(FormBuilderInterface $builder, array $options) {
        parent::buildForm($builder, $options);
        $builder->add('id_parent', 'choice', array(
            'choices' => $this->asCategories,
            'empty_value' => 'Select Status',
            'required' => false,
        ));
        $builder->add('category_name', 'text');
        $builder->add('file', 'file');
        $builder->add('is_active', 'choice', array(
            'choices' => array('1' => 'Active', '0' => 'Inactive'),
            'empty_value' => 'Select Status',
            'required' => true,
        ));
        $builder->add('save', 'submit');
    }

    public function getName() {
        return 'category';
    }

    public function getAllCategories($asCategories) {
        $asCat = array();
        if (!empty($asCategories)) {

            foreach ($asCategories as $asValue) {
                $asCat[$asValue['id']] = $asValue['categoryName'];
            }

            return $asCat;
        }
        return array();
    }

}
