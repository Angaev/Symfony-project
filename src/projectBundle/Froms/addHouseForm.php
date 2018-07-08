<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 08.07.2018
 * Time: 21:39
 */

namespace projectBundle\Froms;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;


class addHouseForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name');

        $builder->add('submit', SubmitType::class ,[
            'label' => 'Добавить'
        ]);
    }
}