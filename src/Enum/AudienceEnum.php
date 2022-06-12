<?php

namespace App\Enum;

final class AudienceEnum extends AbstractEnumType
{
    public const ADULTS = 'adults';
    public const KIDS = 'kids';

    protected static array $choices = [
        self::ADULTS => 'Adults',
        self::KIDS => 'Kids',
    ];

    public static function getDefaultValue(): string
    {
        return self::ADULTS;
    }
}
