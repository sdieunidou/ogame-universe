<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Range;

class PlayerFilterType extends AbstractType
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
                'label' => 'Min score',
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
                'label' => 'Min military',
            ])
        ;
    }
}
