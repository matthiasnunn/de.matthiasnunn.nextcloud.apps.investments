<?php

namespace OCA\Investments\Utils;

use OCA\Investments\AppInfo\Application;


class ImgLoader
{
    public static function getChartLineSolidBlackPath(): string
    {
        return self::getPath("chart-line-solid_black.svg");
    }

    public static function getRightLongSolidNegativePath(): string
    {
        return self::getPath("right-long-solid_negative.svg");
    }

    public static function getRightLongSolidNeutralPath(): string
    {
        return self::getPath("right-long-solid_neutral.svg");
    }

    public static function getRightLongSolidPositivePath(): string
    {
        return self::getPath("right-long-solid_positive.svg");
    }

    private static function getPath(string $fileName): string
    {
        return \OC::$server->getURLGenerator()->imagePath(Application::APP_ID, $fileName);
    }
}