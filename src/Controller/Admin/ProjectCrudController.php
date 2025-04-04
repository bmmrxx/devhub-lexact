<?php

namespace App\Controller\Admin;

use App\Entity\Resources\Project;
use App\Entity\User;
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
        // // Haal alle users op met entity de entity manager, maak een array van choices met daar in alle users
        // $users = $this->em->getRepository(User::class)->findAll();
        // $userChoices = [];
        // foreach ($users as $user) {
        //     $userChoices[$user->getName()] = $user;
        // }

        return [
            TextField::new('name'),
            TextField::new('user.name', 'Leden'),
            // ChoiceField::new('users')
            //     ->setChoices($userChoices)
            //     ->setLabel('Leden')
            //     ->setRequired(false)
            //     ->setHelp('Selecteer de leden van het project')
            //     ->allowMultipleChoices()
        ];
    }
}
