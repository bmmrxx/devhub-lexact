<?php

namespace App\Controller\Admin;

use App\Entity\Resources\Project;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

class ProjectCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Project::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name', 'Projectnaam'),

            Field::new('notesCount', 'Aantal Notities')
                ->hideOnForm(),
            AssociationField::new('users', 'Teamleden')
                ->setFormTypeOptions([
                    'by_reference' => false,
                ])
                ->autocomplete()
                ->formatValue(function ($value, $entity) {
                    $users = $entity->getUsers();
                    return $users->isEmpty() ? '' : implode(', ', $users->map(fn($u) => $u->getName())->toArray());
                })
        ];
    }
}