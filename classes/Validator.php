<?php

namespace App\Classes;

class Validator
{
    public function isValidSearchParam($param): bool
    {
        $validParams = ['title', 'description', 'categories', 'actors', 'release_year'];
        return in_array($param, $validParams);
    }

    public function isValidSearchValue($value): bool
    {
        return !empty($value) && is_string($value);
    }

}
