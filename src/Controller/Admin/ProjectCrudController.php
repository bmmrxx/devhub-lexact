<?php

namespace App\Controller\Admin;

use App\Entity\Resources\Project;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
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
            TextField::new('name', 'Projectnaam'),
            AssociationField::new('users', 'Teamleden')
                ->setFormTypeOptions([
                    'by_reference' => false,
                ])
                ->autocomplete()
                ->formatValue(function ($value, $entity) {
                    // Als er geen users zijn, retourneer lege string
                    if (null === $entity->getUsers() || $entity->getUsers()->isEmpty()) {
                        return '';
                    }

                    // Haal alle gebruikersnamen op
                    $userNames = [];
                    foreach ($entity->getUsers() as $user) {
                        $userNames[] = $user->getName();
                    }

                    return implode(', ', $userNames);
                })
        ];
    }
}
