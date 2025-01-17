<?php

namespace App\Utils;

class QueryStringFormat
{
    public static function toArray(array $requestQuery, $filmId): array
    {
        return collect($requestQuery)
            ->merge(['id' => $filmId])
            ->toArray();
    }
}
