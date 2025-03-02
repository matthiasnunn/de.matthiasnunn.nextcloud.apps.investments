<?php

namespace OCA\Investments\Utils;


class Formatter
{
    public static function toPercentage(float $value): string
    {
        return number_format(
            num: $value,
            decimals: 2,
            decimal_separator: ",",
            thousands_separator: "."
        ) . "%";
    }

    public static function toPercentageWithSign(float $value): string
    {
        $sign = $value >= 0 ? "+" : "";

        return $sign . number_format(
            num: $value,
            decimals: 2,
            decimal_separator: ",",
            thousands_separator: "."
        ) . "%";
    }
}