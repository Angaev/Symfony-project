<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 07.07.2018
 * Time: 19:48
 */

namespace projectBundle\Froms;


use projectBundle\Entity\book;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;


class BookDeleteForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id', HiddenType::class, [
                'data' => $options['delete_id']
            ])
            ->add('submit', SubmitType::class ,[
                'label' => 'Удалить'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'delete_id' => null
        ]);
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['delete_id'] = $options['delete_id'];
    }
}