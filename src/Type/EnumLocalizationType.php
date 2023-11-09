<?php

namespace DevRunDebug\MediaCatalog\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class EnumLocalizationType extends Type
{
    public const NAME = 'enum_localization_type';
    public const TYPE_FILE = 'file';
    public const TYPE_DIRECTORY = 'directory';

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return 'ENUM(\''.implode('\', \'', [self::TYPE_FILE, self::TYPE_DIRECTORY]).'\')';
    }

    public function getName(): string
    {
        return self::NAME;
    }

    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): mixed
    {
        return $value;
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }

    public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): mixed
    {
        if (!in_array($value, [self::TYPE_DIRECTORY, self::TYPE_FILE], true)) {
            throw new \InvalidArgumentException('Invalid status');
        }

        return $value;
    }
}
