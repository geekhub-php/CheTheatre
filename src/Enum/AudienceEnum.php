<?php

namespace App\Enum;

use Fresh\DoctrineEnumBundle\DBAL\Types\AbstractEnumType;

final class AudienceEnum extends AbstractEnumType
{
    public const ADULTS = 'adults';
    public const KIDS = 'kids';

    protected static $choices = [
        self::ADULTS => 'Adults',
        self::KIDS => 'Kids',
    ];

    public static function getDefaultValue(): string
    {
        return self::ADULTS;
    }
}
