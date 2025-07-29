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

class FactureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('id')
            ->add('created_at', null, [
                'widget' => 'single_text',
            ])
            ->add('statutPayement')
            ->add('total_ht')
            ->add('total_tva')
            ->add('total_ttc')
            ->add('client', EntityType::class, [
                'class' => Client::class,
                'choice_label' => 'id',
            ])
            ->add('userFacture', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'id',
            ])
            ->add('devisToFacture', EntityType::class, [
                'class' => Devis::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Facture::class,
        ]);
    }
}
