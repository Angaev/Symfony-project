<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 16.07.2018
 * Time: 20:26
 */

namespace projectBundle\Froms;


use projectBundle\Entity\comment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\SubmitButton;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('commentText');
        $builder->add('Submit', SubmitType::class, [
            'label' => 'Add comment'
        ]);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
       $resolver->setDefaults([
          'data_class' => comment::class
       ]);
    }
}