<?php

namespace App\Form;

use App\Entity\Humain;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;

class MediathequeMembreInscriptionFamilleHumainType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class,
            [
                'label' => "Son nom :",
                'attr' => [
                            'placeholder' => "Veuillez saisir son nom..."
                        ]
            ])
            ->add('prenom', TextType::class,
            [
                'label' => "Son prénom :",
                'attr' => [
                            'placeholder' => "Veuillez saisir ici son prénom..."
                        ]
            ])
            ->add('sexe', ChoiceType::class,
            [
                'label' => "Son sexe :",
                'placeholder' => 'Est-il un homme ou une femme ?',
                'choices'  => [
                    "C'est un homme." => 'h',
                    "C'est une femme." => 'f'
                ]
            ])
            ->add('dateNaissance', BirthdayType::class,
            [
                'label' => "Sa date de naissance :",
                'placeholder' => "Sa date de naissance au format jj/mm/aaaa...",
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
