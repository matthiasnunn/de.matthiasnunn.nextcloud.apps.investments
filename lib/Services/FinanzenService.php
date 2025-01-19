<?php

namespace OCA\Investments\Services;

use OCA\Investments\Repositories\Finanzen100DeRepository;
use OCA\Investments\Repositories\FinanzenNetRepository;


class FinanzenService
{
    public static function getCurrentCourse($link): float
    {
      //return Finanzen100DeRepository::parse($link);
        return FinanzenNetRepository::parse($link);
    }
}