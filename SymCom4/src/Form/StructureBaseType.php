<?php

namespace App\Form;

use App\Entity\Structure;
use Symfony\Component\Form\AbstractType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class StructureBaseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class,
            [
                'label' => "Nom complet :",
                'translation_domain' => 'false'
            ])
            ->add('presentation', CKEditorType::class,
            [
                'label' => "Présentation :",
                'translation_domain' => 'false'
            ])
            ->add('local', CheckboxType::class,
            [
                'label_attr' => ['class' => 'switch-custom'],
                'label' => "Cette structure est Guesninoise.",
                'required' => false,
                'translation_domain' => 'false'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Structure::class,
        ]);
    }
}
