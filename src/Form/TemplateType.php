<?php

/*
Eryk Kucharski
Zarządzanie szablonami
 */

namespace App\Form;

use App\Entity\Template;
use App\Entity\TemplateField;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Defines the form used to create and manipulate templates.
 *
 * @author Eryk Kucharski <eryk.kucharski@gmail.com>
 */
final class TemplateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('systemName', TextType::class, [
                'attr' => ['autofocus' => true],
                'label' => 'template.label.system_name',
            ])
            ->add('displayName', TextType::class, [
                'label' => 'template.label.display_name',
            ])
            ->add('isVisible', CheckboxType::class, [
                'label' => 'template.label.is_visible',
                'required' => false,
            ])
            ->add('fields', CollectionType::class, [
                'entry_type' => TemplateFieldType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'label' => 'template.label.fields',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Template::class,
        ]);
    }
}
