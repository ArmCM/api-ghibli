<?php

namespace App\NullObjects;

class NullQueryString
{
    public function queryString(): string
    {
        return '';
    }
}
