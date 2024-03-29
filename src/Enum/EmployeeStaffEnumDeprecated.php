<?php

namespace App\Enum;

use Fresh\DoctrineEnumBundle\DBAL\Types\AbstractEnumType;

class EmployeeStaffEnumDeprecated extends AbstractEnumType
{
    public const ART_CORE = 'art-core';
    public const ART_PRODUCTION = 'art-production';
    public const ART_DIRECTOR = 'art-director';
    public const ADMINISTRATIVE = 'administrative';
    public const CREATIVE_CORE = 'creative';
    public const INVITED_ACTOR = 'invited';
    public const EPOCH = 'epoch';

    protected static $choices = [
        self::ADMINISTRATIVE => 'Administrative',
        self::ART_DIRECTOR => 'Art-director',
        self::ART_PRODUCTION => 'Art-production',
        self::ART_CORE => 'Art-core',
        self::CREATIVE_CORE => 'Creative',
        self::INVITED_ACTOR => 'Invited actor',
        self::EPOCH => 'Epoch',
    ];

    public static function getDefaultValue(): string
    {
        return self::CREATIVE_CORE;
    }
}
