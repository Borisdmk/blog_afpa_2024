<?php

namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class, [
                'label' => 'Prénom',
                'attr' => [
                    'class' => 'exemple_class_sur_votre_champ',
                    'placeholder' => 'Votre prénom'
                ],
                // 'data' => 'abcdef',
                // 'required'   => false,
                // 'empty_data' => 'John Doe', 
                'row_attr' => ['class' => 'col-md-6', 'id' => '...'],
                
            ])
            ->add('name', TextType::class, [
                'label' => 'Nom',
                'attr' => [
                    'placeholder' => 'Votre nom'
                ],
                'row_attr' => ['class' => 'col-md-6', 'id' => '...'],

                ])
            ->add('object', ChoiceType::class, [
                    'label' => 'Sélectionnez un motif',
                    'choices'  => [
                        'Veuillez choisir une valeur par défaut' => NULL,
                        'Je souhaite devenir formateur' => 'Je souhaite devenir formateur',
                        'Je souhaite devenir développeur' => 'Je souhaite devenir développeur',
                        'Je souhaite entrer en contact' => 'Je souhaite entrer en contact',
                    ],
                    'choice_attr' => [
                        'Veuillez choisir une valeur par défaut' => ['disabled' => true]
                    ],
                ])
            ->add('message', TextareaType::class, [
                    'label' => 'Votre message',
                    'attr' => [
                        'placeholder' => 'Votre message'
                    ],
                    // 'mapped' => false
                    // si t'as un champ dans ton formulaire qui n'est pas lié à l'entité du formulaire
                    // et du coup tu dois dire a symfony, "ca c'est un champ extra n'essaie pas de le lier à l'entité
                    // depuis laquelle tu as créé ce formulaire
                    // ca peut etre pratique si par exemple tu veux demander a la personne d'accepter les conditions
                    // du site pour soumettre les données
                    // l'input check box sera pas forcément lié à un attribut de Contact
                ])
            // ->add('date', DateType::class, [
                
            //     ])
            ->add('save', SubmitType::class, [
                'label' => 'Nous contacter',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}


// 'mapped' => false 
// utilisé si j'ai un champ dans mon formulaire qui ne correspond à aucune colonne de ma classe/entité Contact. 
// Symfony fera pas le lien entre ce champ et Contact pour éviter une erreur