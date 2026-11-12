<?php

namespace LBHurtado\ElectionRules\Support;

final class CanonicalJson
{
    public static function encode(mixed $value): string
    {
        return json_encode($value, JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRESERVE_ZERO_FRACTION);
    }

    public static function sha256(mixed $value): string
    {
        return hash('sha256', self::encode($value));
    }
}
