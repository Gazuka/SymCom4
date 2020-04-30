<?php

namespace App\Form;

use App\Entity\MembreMediatheque;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\Form\MembreMediathequeInscriptionUtilisateurType;

class MembreMediathequeInscriptionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('numCarte', TextType::class,
            [
                'label' => "Identifiant de ma carte Médiathèque :",
                'attr' => [
                            'placeholder' => "Il est indiqué sous le code barre de votre carte... (ex : A 0000)"
                        ]
            ])
            ->add('utilisateur', MembreMediathequeInscriptionUtilisateurType::class,
            [
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => MembreMediatheque::class,
        ]);
    }
}
