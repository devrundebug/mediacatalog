<?php

declare(strict_types=1);

namespace DevRunDebug\MediaCatalog\Exception;

class AppExeception extends \Exception
{
    public static function create(string $message): self
    {
        return new self($message);
    }
}
