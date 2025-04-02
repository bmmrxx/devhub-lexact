<?php

namespace App\Form;

use App\Entity\Resources\Project;
use App\Enum\UserRoleEnum;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ProjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Projectnaam',
                'attr' => [
                    'placeholder' => 'Voer projectnaam in',
                    'class' => 'form-control'
                ]
            ])
            ->add('visibility', ChoiceType::class, [
                'label' => 'Zichtbaarheid',
                'choices' => [
                    'Alleen beheerders' => UserRoleEnum::ADMIN->value,
                    'Mentors + beheerders' => UserRoleEnum::MENTOR->value,
                    'Iedereen intern' => UserRoleEnum::INTERN->value,
                ],
                'attr' => [
                    'class' => 'form-select'
                ],
                'placeholder' => 'Selecteer zichtbaarheid'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Project::class,
        ]);
    }
}
