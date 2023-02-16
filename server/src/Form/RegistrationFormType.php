<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'attr' => ['class' => 'form-control'],
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'This field is required, please enter valid value',
                    ]),
                    new Length([
                        'max' => 255,
                        'maxMessage' => 'Max. length of this field: {{ limit }}',
                    ]),
                    new Email([
                        'message' => 'The email {{ value }} is not a valid email',
                    ])
                ]
            ])
            ->add('name', TextType::class, [
                'label' => 'Your name',
                'help' => 'The field must contain min of 2 characters and max of 255',
                'attr' => ['class' => 'form-control'],
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'This field is required, please enter valid value',
                    ]),
                    new Length([
                        'min' => 2,
                        'max' => 60,
                        'minMessage' => 'Min. Length of this field: {{ limit }}',
                        'maxMessage' => 'Max. length of this field: {{ limit }}',
                    ]),
                ],
            ])
            ->add('username', TextType::class, [
                'label' => 'Username',
                'attr' => ['class' => 'form-control'],
                'required' => false,
                'constraints' => [
                    new Length([
                        'min' => 2,
                        'max' => 20,
                        'minMessage' => 'Min. Length of this field: {{ limit }}',
                        'maxMessage' => 'Max. length of this field: {{ limit }}',
                    ]),
                ],
            ])
            // ->add('agreeTerms', CheckboxType::class, [
            //     'mapped' => false,
            //     'constraints' => [
            //         new IsTrue([
            //             'message' => 'You should agree to our terms.',
            //         ]),
            //     ],
            // ])
            ->add('plainPassword', RepeatedType::class, [
                'first_options'  => ['label' => 'Password'],
                'second_options' => ['label' => 'Repeat Password'],
                'options' => ['attr' => ['class' => 'form-control', 'autocomplete' => 'new-password']],
                'invalid_message' => 'The password fields must match.',
                'type' => PasswordType::class,
                'mapped' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
