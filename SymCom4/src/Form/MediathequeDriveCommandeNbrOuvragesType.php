<?php

namespace App\Form;

use App\Entity\MediathequeDriveCommande;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class MediathequeDriveCommandeNbrOuvragesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nbrLivreChoisi', IntegerType::class, [
                'label' => 'Livres',
                'required' => false,
                'empty_data' => 0
            ])
            ->add('nbrCdChoisi', IntegerType::class, [
                'label' => 'CD',
                'required' => false,
                'empty_data' => 0
            ])
            ->add('nbrDvdChoisi', IntegerType::class, [
                'label' => 'DVD',
                'required' => false,
                'empty_data' => 0
            ])
            ->add('nbrLivreSurprise', IntegerType::class, [
                'label' => 'Livres',
                'required' => false,
                'empty_data' => 0
            ])
            ->add('nbrCdSurprise', IntegerType::class, [
                'label' => 'CD',
                'required' => false,
                'empty_data' => 0
            ])
            ->add('nbrDvdSurprise', IntegerType::class, [
                'label' => 'DVD',
                'required' => false,
                'empty_data' => 0
            ])
            ->add('commentaire', TextareaType::class, [
                'label' => 'Laissez-nous un commentaire sur votre commande si besoin : ',
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => MediathequeDriveCommande::class,
        ]);
    }
}
