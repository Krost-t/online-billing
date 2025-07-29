<?php

namespace App\Form;

use App\Entity\Client;
use App\Entity\Devis;
use App\Entity\Facture;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DevisType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('id')
            ->add('contions')
            ->add('etat')
            ->add('created_at', null, [
                'widget' => 'single_text',
            ])
            ->add('total_ht')
            ->add('total_tva')
            ->add('total_ttc')
            ->add('client', EntityType::class, [
                'class' => Client::class,
                'choice_label' => 'id',
            ])
            ->add('facture', EntityType::class, [
                'class' => Facture::class,
                'choice_label' => 'id',
            ])
            ->add('userDevis', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Devis::class,
        ]);
    }
}
