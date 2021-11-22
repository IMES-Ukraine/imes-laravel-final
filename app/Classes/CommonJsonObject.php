<?php

namespace App\Classes;

class CommonJsonObject
{
    public function __construct($data = [])
    {
        foreach ($data as $key => $value){
            if (in_array($key, get_class_vars(self::class))){
                $this->$key = $value;
            }
        }
    }

    public function toArray(): array
    {
        return (array) $this;
    }
}
