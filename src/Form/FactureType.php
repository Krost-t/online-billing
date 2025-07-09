<?php

namespace App\Form;

use App\Entity\Facture;
use App\Form\LigneFactureType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FactureType extends AbstractType
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
            ->add('dateEmission', DateTimeType::class, [
                'label' => 'Date d’émission',
                'widget' => 'single_text',
                'required' => true,
            ])
            ->add('statut', ChoiceType::class, [
                'label' => 'Statut',
                'choices' => [
                    'Payée' => 'payee',
                    'Impayée' => 'impayee',
                    'En attente' => 'en_attente',
                ],
                'placeholder' => 'Choisissez un statut',
                'required' => true,
            ])
            ->add('totalTTC', MoneyType::class, [
                'label' => 'Total TTC',
                'currency' => 'EUR',
                'required' => true,
            ])
            ->add('lignes', CollectionType::class, [
                'entry_type' => LigneFactureType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'label' => false,
                'prototype' => true,
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Sauvegarder la facture',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Facture::class,
        ]);
    }
}
