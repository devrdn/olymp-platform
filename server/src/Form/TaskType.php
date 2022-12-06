<?php

namespace App\Form;

use App\Entity\Task;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class TaskType extends AbstractType
{
    public function buildForm(
        FormBuilderInterface $builder,
        array $options
    ): void {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Name',
                'help' =>
                    'The field must contain a minimum of 2 characters and a maximum of 255',
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' =>
                            'This field is required, please enter valid value',
                    ]),
                    new Length([
                        'min' => 2,
                        'max' => 5,
                        'minMessage' =>
                            'Min. Length of this field: {{ limit }}',
                        'maxMessage' =>
                            'Max. length of this field: {{ limit }}',
                    ]),
                ],
            ])
            ->add('description')
            ->add('time_limit')
            ->add('memory_limit')
            ->add('example_input')
            ->add('example_output')
            ->add('restriction')
            ->add('TaskMeta', TaskMetaType::class, [
                'by_reference' => false,
            ])
            ->add('save', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Task::class,
        ]);
    }
}
