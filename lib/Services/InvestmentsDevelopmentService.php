<?php

namespace OCA\Investments\Services;

use OCA\Investments\AppInfo\Application;
use OCA\Investments\Repositories\InvestmentsRepository;
use OCA\Investments\Services\InvestmentsService;
use Psr\Log\LoggerInterface;


class InvestmentsDevelopmentService
{
    private InvestmentsRepository $investmentsRepository;
    private InvestmentsService $investmentsService;
    private LoggerInterface $logger;


    public function __construct(InvestmentsRepository $investmentsRepository, InvestmentsService $investmentsService, LoggerInterface $logger)
    {
        $this->investmentsRepository = $investmentsRepository;
        $this->investmentsService = $investmentsService;
        $this->logger = $logger;
    }


    public function getInvestmentsDevelopment(int $last = 30): array
    {
        $developments = $this->investmentsRepository->getInvestmentsDevelopment();

        foreach ($developments as $development)
        {
            // Auf die letzten x Elemente reduzieren
            $development->items = array_slice($development->items, -$last);
        }

        return $developments;
    }


    public function getInvestmentsTrends(): array
    {
        $investmentDevelopments = $this->getInvestmentsDevelopment(last: 2);

        usort($investmentDevelopments, fn($a, $b) => strcmp($a->name, $b->name));

        $investmentsTrends = [];

        foreach ($investmentDevelopments as $investmentDevelopment)
        {
            $items = $investmentDevelopment->items;

            $change = $items[1]->rendite - $items[0]->rendite;

            $investmentsTrends[] = new InvestmentTrend($change, $items[1]->rendite, $investmentDevelopment->name);
        }

        return $investmentsTrends;
    }


    public function updateInvestmentsDevelopments(): void
    {
        $types = [
            "Aktie" => 3,
            "Devise" => 2,
            "ETF" => 4,
            "Rohstoff" => 1
        ];

        foreach ($types as $type => $typeId)
        {
            $this->updateInvestmentsDevelopment($type, $typeId);
        }
    }


    private function updateInvestmentsDevelopment(string $type, int $typeId): void
    {
        $investmentIncludedModel = $this->investmentsService->getInvestmentsByTypeId($typeId)->investmentIncludedModel;

        $totalCurrentPrice = $investmentIncludedModel->totalCurrentPrice;
        $totalPurchasePrice = $investmentIncludedModel->totalPurchasePrice;
        $totalReinertrag = $investmentIncludedModel->totalReinertrag;
        $totalRendite = $investmentIncludedModel->totalRendite;
        $timestamp = $investmentIncludedModel->timestamp;

        if ($totalCurrentPrice === 0)
        {
            $this->logger->error("Fehler beim Aktualisieren der Investmentsentwicklung bei $type: Fehler beim Abfragen.", ["app" => Application::APP_ID]);

            $notificationService = new NotificationService($this->logger);
            $notificationService->createNotification(User::USER, "Fehler beim Aktualisieren der Investmentsentwicklung bei $type: Fehler beim Abfragen.");

            throw new \Exception("Fehler beim Abfragen.");
        }

        $this->investmentsRepository->addInvestmentDevelopment(
            $totalCurrentPrice,
            $totalPurchasePrice,
            $totalReinertrag,
            $totalRendite,
            $timestamp,
            $typeId
        );

        $timestampFormatted = $timestamp->format("d.m.Y");

        $this->logger->info("Investment $type vom $timestampFormatted angelegt: $totalCurrentPrice, $totalPurchasePrice, $totalReinertrag und $totalRendite", ["app" => Application::APP_ID]);
    }
}


class InvestmentTrend
{
    public float $change;
    public float $rendite;
    public string $typeName;

    public function __construct(float $change, float $rendite, string $typeName)
    {
        $this->change = $change;
        $this->rendite = $rendite;
        $this->typeName = $typeName;
    }
}