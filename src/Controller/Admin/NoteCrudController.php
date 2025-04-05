<?php

namespace App\Controller\Admin;

use App\Entity\Resources\Note;
use App\Enum\NoteCategoryEnum;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class NoteCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Note::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('title', 'Titel')
                ->setRequired(true),
            TextEditorField::new('content', 'Inhoud')
                ->setRequired(true),
            AssociationField::new('project', 'Project')
                ->setCrudController(ProjectCrudController::class)
                ->setRequired(true),
            ChoiceField::new('category', 'Categorie')
                ->setChoices($this->getCategoryChoices())
                ->setRequired(true)
                ->allowMultipleChoices(true)
                ->setHelp('Selecteer een categorie'),
            AssociationField::new('user', 'Auteur')
                ->setCrudController(UserCrudController::class),
            DateTimeField::new('created_at', 'Aangemaakt op')
                ->hideOnForm()
                ->setFormat('dd-MM-Y HH:mm')
        ];
    }

    private function getCategoryChoices(): array
    {
        $choices = [];
        foreach (NoteCategoryEnum::cases() as $case) {
            $choices[$case->value] = $case->name;
            $choices[$case->value] = ucfirst(strtolower(str_replace('_', ' ', $case->name)));
        }
        return $choices;
    }
}