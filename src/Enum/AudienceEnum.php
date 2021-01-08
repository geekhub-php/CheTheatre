<?php

namespace App\Enum;

use Fresh\DoctrineEnumBundle\DBAL\Types\AbstractEnumType;

final class AudienceEnum extends AbstractEnumType
{
    public const ADULTS = 'A';
    public const KIDS = 'K';

    protected static $choices = [
        self::ADULTS => 'Adults',
        self::KIDS => 'Kids',
    ];
}