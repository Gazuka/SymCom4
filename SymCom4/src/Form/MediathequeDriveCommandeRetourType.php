<?php

namespace App\Form;

use App\Entity\MediathequeDriveCommande;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class MediathequeDriveCommandeRetourType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('retourLivre', IntegerType::class, [
                'label' => 'Livres',
                'required' => false,
                'empty_data' => 0
            ])
            ->add('retourCD', IntegerType::class, [
                'label' => 'CD',
                'required' => false,
                'empty_data' => 0
            ])
            ->add('retourDVD', IntegerType::class, [
                'label' => 'DVD',
                'required' => false,
                'empty_data' => 0
            ])
            ->add('noteInterne', TextareaType::class, [
                'label' => 'Commentaire privé sur la commande : ',
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
