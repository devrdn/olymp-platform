<?php

namespace App\Form;

use App\Entity\Contest;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

class ContestType extends AbstractType
{
    public function buildForm(
        FormBuilderInterface $builder,
        array $options
    ): void {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Name',
                'help' => 'The field must contain min of 2 characters and max of 255',
                'attr' => ['class' => 'form-control'],
                'required' => true,
                'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'This field is required, please enter valid value',
                    ]),
                    new Length([
                        'min' => 2,
                        'max' => 255,
                        'minMessage' => 'Min. Length of this field: {{ limit }}',
                        'maxMessage' => 'Max. length of this field: {{ limit }}',
                    ]),
                ],
            ])
            ->add('startTime', DateTimeType::class, [
                'label' => 'Start Time',
                'attr' => ['class' => 'form-control'],
                'date_widget' => 'single_text',
                'time_widget' => 'single_text',
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'This field is required, please enter valid value',
                    ]),
                    new Type([
                        'type' => 'DateTime',
                        'message' => 'Value shoud be of type {{ type }}'
                    ])
                ],
            ])
            ->add('endTime', DateTimeType::class, [
                'label' => 'End Time',
                'attr' => ['class' => 'form-control'],
                'date_widget' => 'single_text',
                'time_widget' => 'single_text',
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'This field is required, please enter valid value',
                    ]),
                    new Type([
                        'type' => 'DateTime',
                        'message' => 'Value shoud be of type {{ type }}'
                    ])
                ],
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Create Contest',
                'attr' => ['class' => 'btn btn-primary'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contest::class,
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'csrf_token_id'   => 'contest',
        ]);
    }
}
