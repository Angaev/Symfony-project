<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 08.07.2018
 * Time: 0:00
 */

namespace projectBundle\Froms;


use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use projectBundle\Entity\publishing_house;
use projectBundle\Entity\user;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;



class RenameHouseForm extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add('publishing_house', EntityType::class, array(
           'class' => publishing_house::class,
           'choice_label' => 'name'
        ));
        $builder->add('name');
        $builder->add('submit', SubmitType::class, [
            'label' => 'Переименовать'
        ]);
    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => publishing_house::class,
        ));
    }
}