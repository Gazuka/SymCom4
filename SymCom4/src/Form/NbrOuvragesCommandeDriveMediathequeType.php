<?php

namespace App\Form;

use App\Entity\CommandeDriveMediatheque;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class NbrOuvragesCommandeDriveMediathequeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nbrLivreChoisi', IntegerType::class, [
                'label' => 'Livres',
                'required' => false,
                'data' => 0
            ])
            ->add('nbrCdChoisi', IntegerType::class, [
                'label' => 'CD',
                'required' => false,
                'data' => 0
            ])
            ->add('nbrDvdChoisi', IntegerType::class, [
                'label' => 'DVD',
                'required' => false,
                'data' => 0
            ])
            ->add('nbrLivreSurprise', IntegerType::class, [
                'label' => 'Livres',
                'required' => false,
                'data' => 0
            ])
            ->add('nbrCdSurprise', IntegerType::class, [
                'label' => 'CD',
                'required' => false,
                'data' => 0
            ])
            ->add('nbrDvdSurprise', IntegerType::class, [
                'label' => 'DVD',
                'required' => false,
                'data' => 0
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CommandeDriveMediatheque::class,
        ]);
    }
}
