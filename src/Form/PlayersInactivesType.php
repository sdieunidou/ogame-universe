<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Range;

class PlayersInactivesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('minScore', NumberType::class, [
                'html5' => true,
                'attr' => [
                    'min' => 0,
                ],
                'constraints' => [
                    new NotBlank(),
                    new Range([
                        'min' => 0,
                    ]),
                ],
            ])

            ->add('minMilitaryScore', NumberType::class, [
                'html5' => true,
                'attr' => [
                    'min' => 0,
                ],
                'constraints' => [
                    new NotBlank(),
                    new Range([
                        'min' => 0,
                    ]),
                ],
            ])

            ->add('allowedScoreDiff', NumberType::class, [
                'html5' => true,
                'constraints' => [
                    new NotBlank(),
                ],
            ])
        ;
    }
}
