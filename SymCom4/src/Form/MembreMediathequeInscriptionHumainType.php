<?php

namespace App\Form;

use App\Entity\Humain;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;

class MembreMediathequeInscriptionHumainType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class,
            [
                'label' => "Mon nom :",
                'attr' => [
                            'placeholder' => "Veuillez saisir ici votre nom..."
                        ]
            ])
            ->add('prenom', TextType::class,
            [
                'label' => "Mon prénom :",
                'attr' => [
                            'placeholder' => "Veuillez saisir ici votre prénom..."
                        ]
            ])
            ->add('sexe', ChoiceType::class,
            [
                'label' => "Mon sexe :",
                'placeholder' => 'Etes-vous un homme ou une femme ?',
                'choices'  => [
                    'Je suis un homme.' => 'h',
                    'Je suis une femme.' => 'f'
                ]
            ])
            ->add('dateNaissance', BirthdayType::class,
            [
                'label' => "Ma date de naissance :",
                'placeholder' => "Ma date de naissance au format jj/mm/aaaa...",
                'widget' => "single_text"
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Humain::class,
        ]);
    }
}
