<?php

namespace App\Form;

use App\Entity\TaskMeta;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TaskMetaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('author', TextType::class, [
                'label' => 'Author [#todo: will be deleted]',
                'attr' => ['class' => 'form-control']
            ])
            ->add('solved')
            ->add('complexity')
            ->add('source');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TaskMeta::class,
        ]);
    }
}
