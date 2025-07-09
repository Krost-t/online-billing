<?php

namespace App\Form;

use App\Entity\Devis;
use App\Form\LigneDevisType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DevisType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('clientNom', TextType::class, [
                'label' => 'Nom du client',
                'required' => true,
            ])
            ->add('clientEmail', EmailType::class, [
                'label' => 'Email du client',
                'required' => true,
            ])
            ->add('createdAt', DateTimeType::class, [
                'label' => 'Date de création',
                'widget' => 'single_text',
                'required' => true,
            ])
            ->add('statut', ChoiceType::class, [
                'label' => 'Statut',
                'choices' => [
                    'En attente' => 'en_attente',
                    'Validé' => 'valide',
                    'Annulé' => 'annule',
                ],
                'required' => true,
                'placeholder' => 'Choisissez un statut',
            ])
            ->add('lignes', CollectionType::class, [
                'entry_type' => LigneDevisType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'label' => false,
                'prototype' => true,
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Sauvegarder',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Devis::class,
        ]);
    }
}
