<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Range;

class GalaxyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $server = $options['server'];

        $builder
            ->add('galaxy', NumberType::class, [
                'html5' => true,
                'attr' => [
                    'min' => 1,
                    'max' => $server->getGalaxies(),
                ],
                'constraints' => [
                    new NotBlank(),
                    new Range([
                        'min' => 1,
                        'max' => $server->getGalaxies(),
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired([
            'server',
        ]);
    }
}
