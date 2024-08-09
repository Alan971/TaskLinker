<?php

namespace App\Enum;

enum ContractList: string
{
    case CDI = 'CDI';
    case CDD = 'CDD';
    case STAGIAIRE  = 'STAGIAIRE';
    case FREELANCE = 'FREELANCE';
    
    public function getLabel(): string
    {
        return match ($this) {
            self::CDI => 'CDI',
            self::CDD => 'CDD',
            self::STAGIAIRE => 'STAGIAIRE',
            self::FREELANCE => 'FREELANCE',
        };
    }
}