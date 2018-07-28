<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 19.07.2018
 * Time: 20:50
 */

namespace projectBundle\Froms;


use Symfony\Component\Form\AbstractType;
use projectBundle\Entity\user;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserEditForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('password')
            ->add('submit', SubmitType::class ,[
                'label' => 'Изменить'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
           'data_class' => user::class
        ]);
    }
}