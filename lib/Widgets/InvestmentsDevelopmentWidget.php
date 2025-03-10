<?php

namespace OCA\Investments\Widgets;

use OCA\Investments\AppInfo\Application;
use OCA\Investments\Services\InvestmentsDevelopmentService;
use OCA\Investments\Services\InvestmentTrend;
use OCA\Investments\Utils\Formatter;
use OCA\Investments\Utils\ImgLoader;
use OCP\IInitialStateService;
use OCP\IL10N;
use OCP\IURLGenerator;
use OCP\Util;
use OCP\Dashboard\IAPIWidgetV2;
use OCP\Dashboard\IIconWidget;
use OCP\Dashboard\IWidget;
use OCP\Dashboard\Model\WidgetItem;
use OCP\Dashboard\Model\WidgetItems;


class InvestmentsDevelopmentWidget implements IAPIWidgetV2, IIconWidget, IWidget
{
    private IInitialStateService $initialStateService;
    private InvestmentsDevelopmentService $investmentsDevelopmentService;
    private IL10N $l10n;
    private IURLGenerator $urlGenerator;

    public function __construct(IInitialStateService $initialStateService, InvestmentsDevelopmentService $investmentsDevelopmentService, IL10N $l10n, IURLGenerator $urlGenerator)
    {
        $this->initialStateService = $initialStateService;
        $this->investmentsDevelopmentService = $investmentsDevelopmentService;
        $this->l10n = $l10n;
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * @inheritDoc
     * @return string css class that displays an icon next to the widget title
     */
    public function getIconClass(): string
    {
        return "";
    }

    /**
     * @inheritDoc
     * @return string url to icon next to the widget
     */
    public function getIconUrl(): string
    {
        return ImgLoader::getChartLineSolidBlackPath();
    }

    /**
     * @inheritDoc
     * @return string Unique id that identifies the widget, e.g. the app id
     */
    public function getId(): string
    {
        return "investmentsdevelopmentwidget";
    }

    /**
     * @inheritDoc
     */
    public function getItemsV2(string $userId, string|null $since = null, int $limit = 7): WidgetItems
    {
        $investmentsTrends = $this->investmentsDevelopmentService->getInvestmentsTrends();

        $widgetItems = array_map([$this, "toWidgetItem"], $investmentsTrends);

        return new WidgetItems($widgetItems);
    }

    /**
     * @inheritDoc
     * @return int Initial order for widget sorting in the range of 10-100, 0-9 are reserved for shipped apps
     */
    public function getOrder(): int
    {
        return 10;
    }

    /**
     * @inheritDoc
     * @return string User facing title of the widget
     */
    public function getTitle(): string
    {
        return $this->l10n->t("Investments (Rendite)");
    }

    /**
     * @inheritDoc
     * @return string|null The absolute url to the apps own view
     */
    public function getUrl(): string|null
    {
        return null;
    }

    /**
     * @inheritDoc
     * Execute widget bootstrap code like loading scripts and providing initial state
     */
    public function load(): void
    {
        // $this->initialStateService->provideInitialState(Application::APP_ID, "keyMitDenenDieDatenAbrufbarSind", "DIE Daten");

        // Util::addScript(Application::APP_ID, "widget");
    }

    private function determineIcon(float $rendite): string
    {
        if ($rendite <= -0.2)
        {
            return ImgLoader::getRightLongSolidNegativePath();
        }

        if ($rendite > -0.2 && $rendite < 0.2)
        {
            return ImgLoader::getRightLongSolidNeutralPath();
        }

        if ($rendite >= 0.2)
        {
            return ImgLoader::getRightLongSolidPositivePath();
        }
    }

    private function mapInvestmentTypeNameToPlural(string $name)
    {
        if ($name === "Aktie") return "Aktien";
        if ($name === "Devise") return "Devisen";
        if ($name === "ETF") return "ETFs";
        if ($name === "Rohstoff") return "Rohstoffe";
    }

    private function toWidgetItem(InvestmentTrend $investmentTrend): WidgetItem
    {
        $namePlural = $this->mapInvestmentTypeNameToPlural($investmentTrend->typeName);

        $title = $namePlural;
        $subtitle = Formatter::toPercentage($investmentTrend->rendite) . " " . "(" . Formatter::toPercentageWithSign($investmentTrend->change) . ")";
        $link = $this->urlGenerator->linkToRouteAbsolute(Application::APP_ID.".main.investment".$namePlural);
        $iconUrl = $this->determineIcon($investmentTrend->change);

        return new WidgetItem(
            title: $title,
            subtitle: $subtitle,
            link: $link,
            iconUrl: $iconUrl
        );
    }
}