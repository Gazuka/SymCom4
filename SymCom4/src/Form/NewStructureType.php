<?php

namespace App\Form;

use App\Entity\Structure;
use App\Form\NewStructureNewLienType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class NewStructureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class,
            [
                'label' => "Nom complet :"
            ])
            ->add('presentation', TextareaType::class,
            [
                'label' => "Présentation :"
            ])
            ->add('local', CheckboxType::class,
            [
                'label_attr' => ['class' => 'switch-custom'],
                'label' => "Cette structure est Guesninoise.",
            ])
            ->add('lien', NewStructureNewLienType::class,
            [])
            //->add('contacts')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Structure::class,
        ]);
    }
}
