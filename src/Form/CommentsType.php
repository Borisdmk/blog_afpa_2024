<?php

namespace App\Form;

use App\Entity\article;
use App\Entity\Comments;
use App\Entity\user;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('comment');
        //     ->add('date', null, [
        //         'widget' => 'single_text',
        //     ])
        //     ->add('id_article', EntityType::class, [
        //         'class' => article::class,
        //         'choice_label' => 'id',
        //     ])
        //     ->add('id_user', EntityType::class, [
        //         'class' => user::class,
        //         'choice_label' => 'id',
        //     ])
        // ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Comments::class,
        ]);
    }
}
