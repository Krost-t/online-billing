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
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use App\Enum\EtatFacture;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class FactureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('id')
            ->add('createdAt', DateTimeType::class, [
                'widget' => 'single_text',
            ])
            // on bind direct à l’enum, plus propre
            ->add('statutPayement', EnumType::class, [
                'class' => EtatFacture::class,
                'choice_label' => fn(EtatFacture $e) => $e->label(),
            ])
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
