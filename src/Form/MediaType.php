<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Media;
use App\Entity\Category;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class MediaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('filePath')
            ->add('description', TextareaType::class)
            // ->add('slug')
            ->add('categories', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'label',
                // 'mapped' => false  // ne pas mettre ne vas pas en base de donnée
                'multiple' => true,
                'expanded' => true,
                'attr' => array('checked' => 'checked'),
            ])
            // Comment ajouter un user au formulaire
            // Pas besoin parce qu'on prend l'utilisateur connecté
            // ->add('user', EntityType::class, [  // car user est de type entité dc entitytype
            //     'class' => User::class,
            //     'choice_label' => 'email',  //qu'est qui va etre afficher dans le champ lié a un champ de user, les propriete de l'entite user
            //     'query_builder' => function (EntityRepository $er) {
            //         return $er->createQueryBuilder('u')
            //         ->orderBy('u.email', 'ASC');
            //     },
            //     'expanded' => true,         // met en checkbox radio
            // ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Media::class,
        ]);
    }
}
