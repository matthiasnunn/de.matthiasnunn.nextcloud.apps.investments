<?php

namespace OCA\Investments\Providers;

use OCA\Investments\AppInfo\Application;
use OCP\IURLGenerator;


class IconUrlProvider
{
    private IURLGenerator $urlGenerator;

    public function __construct(IURLGenerator $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    public function getChartLineSolidBlackPath(): string
    {
        return $this->getPath("chart-line-solid_black.svg");
    }

    public function getRightLongSolidNegativePath(): string
    {
        return $this->getPath("right-long-solid_negative.svg");
    }

    public function getRightLongSolidNeutralPath(): string
    {
        return $this->getPath("right-long-solid_neutral.svg");
    }

    public function getRightLongSolidPositivePath(): string
    {
        return $this->getPath("right-long-solid_positive.svg");
    }

    private function getPath(string $fileName): string
    {
        return $this->urlGenerator->imagePath(Application::APP_ID, $fileName);
    }
}