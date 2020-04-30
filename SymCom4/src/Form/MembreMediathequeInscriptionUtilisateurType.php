<?php

namespace App\Form;

use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class MembreMediathequeInscriptionUtilisateurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            //->add('pseudo')
            //->add('password')
            ->add('email', TextType::class,
            [
                'label' => "Mon adresse e-mail :",
                'attr' => [
                            'placeholder' => "Veuillez saisir ici votre adresse e-mail..."
                        ]
            ])
            ->add('humain', MembreMediathequeInscriptionHumainType::class,
            [
            ])
            //->add('roles')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}
