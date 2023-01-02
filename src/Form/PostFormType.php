<?php

namespace App\Form;

use App\Entity\Post;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class PostFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class)
            ->add('category', EntityType::class, [
                // looks for choices from this entity
                'class' => Category::class,
            
                // uses the Category.name property as the visible option string
                'choice_label' => 'name',

                'placeholder'  => "Choisissez une catégorie"
            ])
            ->add('imageFile', VichImageType::class, [
                'required' => false,
                // 'allow_delete' => true,
                // 'delete_label' => '...',
                // 'download_label' => '...',
                // 'download_uri' => true,
                // 'image_uri' => true,
                // 'imagine_pattern' => '...',
                // 'asset_helper' => true,
            ]
    )
            ->add('content', TextareaType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
