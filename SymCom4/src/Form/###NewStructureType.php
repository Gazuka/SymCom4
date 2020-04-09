<?php

namespace App\Form;

use App\Entity\Image;
use App\Entity\Structure;
use App\Repository\ImageRepository;
use App\Form\NewStructureNewLienType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
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
                'required' => false
            ])
            /*->add('lien', NewStructureNewLienType::class,
            [
                'required' => false
            ])*/
            ->add('image', EntityType::class,
            [
                'label' => 'Illustration de la structure :',
                'class' => Image::class,
                'choice_label' => 'media.recupAsset',
                'expanded' => true,
                'multiple' => false,
                'required' => false,
                'query_builder' => function (ImageRepository $repo)
                {
                    return $repo->form_FindAll('images');
                }
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
