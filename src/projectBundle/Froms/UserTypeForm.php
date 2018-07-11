<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 11.07.2018
 * Time: 21:44
 */

namespace projectBundle\Froms;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserTypeForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class)->add('email', EmailType::class)
                ->add('plainPassword', RepeatedType::class, [
                   'type' => PasswordType::class,
                    'first_options' => ['label' => 'Пароль'],
                    'second_options' => ['label' => 'Повторите пароль'],
                ]);
        $builder->add('submit', SubmitType::class ,[
            'label' => 'Регистрация'
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'projectBundle\Entity\user',
        ]);
    }
}