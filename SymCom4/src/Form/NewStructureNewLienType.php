<?php

namespace App\Form;

use App\Entity\Lien;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class NewStructureNewLienType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            //->add('label')
            //->add('fontAwesome')
            ->add('url', UrlType::class,
            [
                'label' => "Adresse complète du site Internet :"
            ])
            //Le lien vers un site d'une structure sera forcément ouvert dans un nouvel onglet
            ->add('extern', HiddenType::class,
            [
                'data' => true,
            ])
            //->add('colorBoot')
            //->add('page')
            //->add('structure')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Lien::class,
        ]);
    }
}
