<?php

namespace App\Form;

use App\Entity\MediathequeMembre;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\Form\MediathequeMembreInscriptionFamilleUtilisateurType;

class MediathequeMembreInscriptionFamilleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('numCarte', TextType::class,
            [
                'label' => "Identifiant de sa carte Médiathèque :",
                'attr' => [
                            'placeholder' => "Il est indiqué sous le code barre de la carte... (ex : A 0000)"
                        ]
            ])
            ->add('utilisateur', MediathequeMembreInscriptionFamilleUtilisateurType::class,
            [
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => MediathequeMembre::class,
        ]);
    }
}
