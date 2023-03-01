<?php

namespace App\Form;

use App\Entity\Task;
use App\Entity\TaskTest;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Length;

class TaskTestType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('input_pattern', TextType::class, [
                'label' => 'Input Pattern',
                'attr' => ['class' => 'form-control'],
                'help' => "e.g. `[id]_input.txt`, where [id] some identifier",
                'required' => false,
                'mapped' => false,
                'constraints' => [
                    new Length([
                        'max' => 40,
                        'maxMessage' => 'Max. length of this field: {{ limit }}',
                    ])
                ]
            ])
            ->add('output_pattern', TextType::class, [
                'label' => 'Output pattern',
                'attr' => ['class' => 'form-control'],
                'help' => "e.g. `[id]_output.txt`, where [id] test identifier",
                'required' => false,
                'mapped' => false,
                'constraints' => [
                    new Length([
                        'max' => 40,
                        'maxMessage' => 'Max. length of this field: {{ limit }}',
                    ])
                ]
            ])
            ->add('tests', FileType::class, [
                'mapped' => false,
                'required' => true,
                'attr' => ['class' => 'form-control', 'accept' => ".zip"],
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'application/zip',
                        ],
                    ])
                ]
            ])
            ->add('save', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TaskTest::class,
        ]);
    }
}
