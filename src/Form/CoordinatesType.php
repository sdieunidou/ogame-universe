<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Range;

class CoordinatesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('galaxy', NumberType::class, [
                'html5' => true,
                'attr' => [
                    'min' => 1,
                    'max' => 9,
                ],
                'constraints' => [
                    new NotBlank(),
                    new Range([
                        'min' => 1,
                        'max' => 9,
                    ]),
                ],
            ])

            ->add('system', NumberType::class, [
                'html5' => true,
                'attr' => [
                    'min' => 1,
                    'max' => 499,
                ],
                'constraints' => [
                    new NotBlank(),
                    new Range([
                        'min' => 1,
                        'max' => 499,
                    ]),
                ],
            ])
        ;
    }
}
