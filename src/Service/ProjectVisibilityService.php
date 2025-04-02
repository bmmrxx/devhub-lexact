<?php

namespace App\Service;

class ProjectVisibilityService
{
    public const VISIBILITY_PRIVATE = 'PRIVATE';
    public const VISIBILITY_INTERN = 'INTERN';
    public const VISIBILITY_MENTOR = 'MENTOR';

    public function changeVisibility($project, string $newVisibility): void
    {
        if (!in_array($newVisibility, $this->getAllowedVisibilities())) {
            throw new \InvalidArgumentException('Niet gemachtigd om dit bestand te bekijken');
        }

        // Reflection om private property aan te passen
        $reflection = new \ReflectionClass($project);
        $property = $reflection->getProperty('visibility');
        $property->setAccessible(true);
        $property->setValue($project, $newVisibility);
    }

    public function getAllowedVisibilities(): array
    {
        return [
            self::VISIBILITY_PRIVATE,
            self::VISIBILITY_INTERN,
            self::VISIBILITY_MENTOR
        ];
    }
}

// $service->changeVisibility($project, ProjectVisibilityService::VISIBILITY_INTERN);