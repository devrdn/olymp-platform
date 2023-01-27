<?php

namespace App\Form;

use App\Config\AllowedExtesion;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Expression;

class UploadSolutionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('language', EnumType::class, [
                'label' => "Programming Language",
                'class' => AllowedExtesion::class,
            ])
            ->add('file_solution', FileType::class, [
                'attr' => ['class' => 'form-control'],
                'required' => false,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('text_solution', TextareaType::class, [
                'required' => false
            ])
            ->add('save', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
