<?php

namespace App\Utils;

use Illuminate\Support\Str;

class QueryString
{
    public static function toArray(string|null $queryString): array
    {
        if (is_null($queryString)) {
            return [];
        }

        return collect(self::splitQueryString($queryString))
            ->mapWithKeys(fn ($pair) => self::createKeyValueArray($pair))
            ->mapWithKeys(fn ($value, $key) => [self::normalizeKeys($key) => $value])
            ->toArray();
    }

    protected static function splitQueryString(string $queryString): array
    {
        return explode(',',  urldecode($queryString));
    }

    protected static function createKeyValueArray($pair): array
    {
        [$key, $value] = array_pad(explode('=', $pair, 2), 2, null);

        return [$key => $value];
    }

    protected static function normalizeKeys(string $key): string
    {
        return Str::replace('_', '-', $key);
    }
}
