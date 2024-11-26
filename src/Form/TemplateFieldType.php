<?php

namespace App\Form;

use App\Entity\TemplateField;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Form\CallbackTransformer;

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
            ->add('parameters', TextType::class, [
                'label' => 'template.label.parameters',
                'required' => false,
                'help' => 'Dla pola tekstowego: {"maxLength": 255}, dla listy rozwijanej: {"options": ["opcja1", "opcja2"]}',
                'attr' => [
                    'placeholder' => 'Wprowadź parametry w formacie JSON'
                ],
                'invalid_message' => 'Wprowadź poprawny format JSON'
            ]);

        $builder->get('parameters')
            ->addModelTransformer(new CallbackTransformer(
                function ($parametersAsArray) {
                    if (null === $parametersAsArray) {
                        return '';
                    }
                    return json_encode($parametersAsArray, JSON_PRETTY_PRINT);
                },
                function ($parametersAsString) {
                    if (empty($parametersAsString)) {
                        return [];
                    }
                    
                    $decoded = json_decode($parametersAsString, true);
                    if (json_last_error() !== JSON_ERROR_NONE) {
                        throw new TransformationFailedException('Nieprawidłowy format JSON');
                    }
                    
                    return $decoded;
                }
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TemplateField::class,
        ]);
    }
} 