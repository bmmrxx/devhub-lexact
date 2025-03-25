<?php

namespace App\Enum;

enum UploadCategoryEnum: string
{
    case DAILY = 'daily';
    case SCHOOL = 'school';
    case QUESTIONS = 'questions';
}
