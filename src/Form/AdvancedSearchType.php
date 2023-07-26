<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdvancedSearchType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('start_date', DateType::class, [
                'widget' => 'single_text',
                // Ajoutez ici les options spécifiques pour le champ de date de début
            ])
            ->add('end_date', DateType::class, [
                'widget' => 'single_text',
                // Ajoutez ici les options spécifiques pour le champ de date de fin
            ]);
        // Ajoutez ici d'autres champs de formulaire pour votre recherche avancée
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configurez ici les options par défaut pour le formulaire
        ]);
    }
}
