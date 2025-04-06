<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Enum\UserRoleEnum;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Validator\Constraints\Length;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('User')
            ->setEntityLabelInPlural('Users')
            ->setSearchFields(['email', 'name'])
            ->setDefaultSort(['created_at' => 'DESC']);
    }

    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('name');
        yield EmailField::new('email');
        yield TextField::new('password')
            ->setFormType(PasswordType::class)
            ->setFormTypeOptions([
                'constraints' => [
                    new Length(['min' => 8, 'minMessage' => 'Minimaal 8 karakters']),
                ],
                'attr' => ['minlength' => 8]
            ])
            ->onlyOnForms();

        yield ChoiceField::new('roles')
            ->setChoices($this->getRoleChoices())
            ->allowMultipleChoices()
            ->renderExpanded()
            ->renderAsBadges([
                'ROLE_ADMIN' => 'success',
                'ROLE_INTERN' => 'primary',
                'ROLE_MENTOR' => 'warning',
            ]);

        yield DateTimeField::new('createdAt')
            ->hideOnForm();
    }

    private function getRoleChoices(): array
    {
        $choices = [];
        foreach (UserRoleEnum::cases() as $role) {
            $choices[$role->value] = $role->value;
        }
        return $choices;
    }
}