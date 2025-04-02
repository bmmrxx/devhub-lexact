<?php

namespace App\Controller\Admin;

use App\Entity\Resources\Project;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use App\Enum\UserRoleEnum;

class ProjectCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Project::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name'),
            ChoiceField::new('visibility')
                ->setChoices([
                    'Admin' => UserRoleEnum::ADMIN->value,
                    'Mentor' => UserRoleEnum::MENTOR->value,
                    'Intern' => UserRoleEnum::INTERN->value
                ])
        ];
    }
}
