<?php

namespace App\Form;

use App\Entity\TemplateField;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TemplateFieldType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('systemName', TextType::class, [
                'label' => 'template.label.system_name',
                'attr' => [
                    'placeholder' => 'np. tytulArtykulu (systemowy)'
                ]
            ])
            ->add('displayName', TextType::class, [
                'label' => 'template.label.display_name',
                'attr' => [
                    'placeholder' => 'np. Tytuł artykułu (wyświetlany)'
                ]
            ])
            ->add('isRequired', CheckboxType::class, [
                'label' => 'template.label.is_required',
                'required' => false,
            ])
            ->add('fieldType', ChoiceType::class, [
                'label' => 'template.label.field_type',
                'choices' => [
                    'template.label.text' => 'text',
                    'Data' => 'date',
                    'template.label.datetime' => 'datetime',
                    'template.label.select' => 'select'
                ],
            ])
            ->add('parameters', CollectionType::class, [
                'label' => 'template.label.parameters',
                'required' => false,
                'entry_type' => TextType::class,
                'entry_options' => [
                    'attr' => [
                    'placeholder' => '{"maxLength": 255} lub {"options": ["opcja1", "opcja2"]}'
                    ]
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TemplateField::class,
        ]);
    }
} 