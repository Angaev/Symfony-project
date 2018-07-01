<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 30.06.2018
 * Time: 23:33
 */

namespace projectBundle\Froms;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name')->add('year')->add("description")->add('link')->add('publishing_house');
        $builder->add('submit', SubmitType::class ,[
           'label' => 'Добавить'
        ]);
    }
}