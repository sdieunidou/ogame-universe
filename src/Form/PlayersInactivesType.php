<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PlayersInactivesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('minScore', NumberType::class, [
                'html5' => true,
            ])
            ->add('minMilitaryScore', NumberType::class, [
                'html5' => true,
            ])
            ->add('allowedScoreDiff', NumberType::class, [
                'html5' => true,
            ])
        ;
    }
}
