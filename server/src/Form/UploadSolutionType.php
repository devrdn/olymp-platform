<?php

namespace App\Form;

use App\Config\AllowedExtensions;
use App\EventSubscriber\OnlyOneFieldFilledSubscriber;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UploadSolutionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('language', EnumType::class, [
                'label' => "Programming Language",
                'class' => AllowedExtensions::class,
                'attr' => [
                    'class' => 'border border-white w-full outline-none p-2 bg-transparent focus:border  focus:border-blue-500 ease-in-out duration-300 text-blue-500 text-white'
                ],
            ])
            ->add('file_solution', FileType::class, [
                'required' => false,
                'attr' => ['class' => 'relative m-0 block w-full min-w-0 flex-auto rounded border border-solid  bg-clip-padding px-3 py-[0.32rem] text-blue-500 font-normal text-white file:cursor-pointer cursor-pointer transition duration-300 ease-in-out file:-mx-3 file:-my-[0.32rem] file:overflow-hidden file:rounded-none file:border-0 file:border-blue-500  file:bg-blue-600 file:px-3 file:py-[0.32rem] file:text-white file:transition file:duration-150 file:ease-in-out file:[border-inline-end-width:1px] file:[margin-inline-end:0.75rem] hover:file:bg-blue-500 focus:border-primary focus:shadow-te-primary focus:outline-none'],
            ])
            ->add('text_solution', TextareaType::class, [
                'required' => false,
                'attr' => ['class' => 'border border-white w-full outline-none p-2 bg-transparent focus:border  focus:border-blue-500 ease-in-out duration-300'],
            ])
            ->add('save', SubmitType::class, [
                'attr' => ['class' => 'btn-link mt-4'],
            ]);

        $builder->addEventSubscriber(new OnlyOneFieldFilledSubscriber([
            'file_solution',
            'text_solution'
        ]));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
