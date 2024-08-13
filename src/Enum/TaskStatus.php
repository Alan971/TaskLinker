<?php

namespace App\Enum;

use UnitEnum;

enum TaskStatus: string
{
    case TODO = 'To Do';
    case DOING = 'Doing';
    case DONE  = 'Done';
    
    public function getLabel(): string
    {
        return match ($this) {
            self::TODO => 'To Do',
            self::DOING => 'Doing',
            self::DONE => 'Done',
        };
    }
    // cette methode permet de récupérer les valeurs de la liste 
    // voir le queryBuilder pour l'utilisation de celle-ci
    public function getValues(): array
    {
        $case = self::cases();
        return array_map(static fn(UnitEnum $case) => $case->value, $case);
    }

}