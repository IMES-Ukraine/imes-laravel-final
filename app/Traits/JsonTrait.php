<?php

namespace App\Traits;

class JsonTrait
{
    protected function asJson($value)
    {
        return json_encode($value, JSON_UNESCAPED_UNICODE);
    }
}
