<?php

namespace App\Form;

use App\Entity\Post;
use App\Entity\User;
use Doctrine\DBAL\Types\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('image', FileType::class, [
                'attr' => [
                    'class' => 'post-img',
                    'placeholder' => 'Upload image or video'
                ],
                'label' => '+',
                'label_attr' => [
                    'class' => 'post-img-lb'
                ]
            ])
            ->add('description', TextareaType::class, [
                'attr' => [
                    'class' => 'post-desc',
                    'placeholder' => 'Description...'
                ],
                'label' => false
            ])
            ->add('category', EntityType::class, [
                'class' => User::class,
            ])
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'post-sub'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
