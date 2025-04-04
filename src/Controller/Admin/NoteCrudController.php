<?php

namespace App\Controller\Admin;

use App\Entity\Resources\Note;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
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
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('content'),
            TextField::new('project.name', 'Project'),
            //Maak hier een choicefield van, alle users uit de database ophalen en deze in een array zetten
            TextField::new('user.name', 'Creator'),
        ];
    }
}
