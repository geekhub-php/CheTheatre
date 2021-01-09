<?php

namespace App\Enum;

use Fresh\DoctrineEnumBundle\DBAL\Types\AbstractEnumType;

class EmployeeStaffEnum extends AbstractEnumType
{
    public const ART_CORE = 'art-core';
    public const ART_PRODUCTION = 'art-production';
    public const ADMINISTRATIVE = 'administrative';
    public const CREATIVE_CORE = 'creative';
    public const INVITED_ACTOR = 'invited';

    protected static $choices = [
        self::ART_CORE => 'Art-core',
        self::ART_PRODUCTION => 'Art-production',
        self::ADMINISTRATIVE => 'Administrative',
        self::CREATIVE_CORE => 'Creative',
        self::INVITED_ACTOR => 'Invited actor',
    ];

    public static function getDefaultValue(): string
    {
        return self::CREATIVE_CORE;
    }
}