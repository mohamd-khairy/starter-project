<?php

namespace App\Enums;

use ReflectionClass;

enum EventTypeEnum: int
{
    case people = 1;
    case vehicles = 2;
    case leakage = 3;
    case fire = 4;
    case smoke = 5;
    case change = 6;

    static function getConstants()
    {
        $oClass = new ReflectionClass(__CLASS__);
        return $oClass->getConstants();
    }

    static function case($key)
    {
        return EventTypeEnum::getConstants()[$key]->value;
    }
}
