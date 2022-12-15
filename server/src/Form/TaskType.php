<?php

namespace App\Form;

use App\Entity\Task;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

class TaskType extends AbstractType
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
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'help' => 'The field must contain max of 2000 characters',
                'attr' => ['class' => 'form-control'],
                'required' => true,
                'constraints' => [
                    new Length([
                        'min' => 1,
                        'max' => 2000,
                        'minMessage' => 'Min. Length of this field: {{ limit }}',
                        'maxMessage' => 'Max. length of this field: {{ limit }}',
                    ]),
                ]
            ])
            ->add('time_limit', NumberType::class, [
                'label' => 'Time limit',
                'attr' => ['class' => 'form-control'],
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'This field is required, please enter valid value',
                    ]),
                    new Type([
                        'type' => 'numeric',
                        'message' => 'Value shoud be of type {{ type }}'
                    ])
                ],
            ])
            ->add('memory_limit', NumberType::class, [
                'label' => 'Memory limit',
                'attr' => ['class' => 'form-control'],
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'This field is required, please enter valid value',
                    ]),
                    new Type([
                        'type' => 'numeric',
                        'message' => 'Value shoud be of type {{ type }}'
                    ])
                ],
            ])
            ->add('example_input', TextareaType::class, [
                'label' => 'Example Input',
                'required' => false,
                'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new Length([
                        'min' => 1,
                        'max' => 2000,
                        'minMessage' => 'Min. Length of this field: {{ limit }}',
                        'maxMessage' => 'Max. length of this field: {{ limit }}',
                    ])
                ]
            ])
            ->add('example_output', TextareaType::class, [
                'label' => 'Example Output',
                'attr' => ['class' => 'form-control'],
                'required' => false,
                'constraints' => [
                    new Length([
                        'min' => 1,
                        'max' => 2000,
                        'minMessage' => 'Min. Length of this field: {{ limit }}',
                        'maxMessage' => 'Max. length of this field: {{ limit }}',
                    ])
                ]
            ])
            ->add('restriction', TextareaType::class, [
                'label' => 'Restriction',
                'attr' => ['class' => 'form-control'],
                'required' => false,
                'constraints' => [
                    new Length([
                        'min' => 1,
                        'max' => 1000,
                        'minMessage' => 'Min. Length of this field: {{ limit }}',
                        'maxMessage' => 'Max. length of this field: {{ limit }}',
                    ])
                ]
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Save Task',
                'attr' => ['class' => 'btn btn-primary'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Task::class,
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'csrf_token_id'   => 'task',
        ]);
    }
}
