<?php

namespace App\Enums;

use ReflectionClass;

enum DetectionStatusEnum: int
{
    case error = 0;
    case pending = 1;
    case confirmed = 2;

    static function getConstants()
    {
        $oClass = new ReflectionClass(__CLASS__);
        return $oClass->getConstants();
    }

    static function case($key)
    {
        return DetectionStatusEnum::getConstants()[$key]->value;
    }
}
