<?php

namespace OCA\Investments;

use OCA\Investments\AppInfo\Application;
use OCA\Investments\Services\ApiService;
use OCA\Investments\Services\InvestmentsService;
use OCA\Shared\AppInfo\User;
use OCA\Shared\Services\NotificationService;
use OCA\Shared\Services\UserFilesService;
use OCP\ILogger;
use OCP\Http\Client\IClientService;


class InvestmentsDevelopmentUpdate
{
    private $clientService;
    private $investmentsService;
    private $logger;


    public function __construct(IClientService $clientService, ILogger $logger, UserFilesService $userFilesService)
    {
        $this->clientService = $clientService;
        $this->investmentsService = new InvestmentsService($userFilesService);
        $this->logger = $logger;
    }


    public function updateAll(): void
    {
        $types = [
            "Aktie" => 3,
            "Devise" => 2,
            "ETF" => 4,
            "Rohstoff" => 1
        ];

        foreach ($types as $type => $typeId)
        {
            self::update($type, $typeId);
        }
    }


    public function update(string $type, string $typeId): void
    {
        $investmentIncludedModel = ApiService::getInvestmentsByType($this->clientService, $type)->investmentIncludedModel;

        $totalCurrentPrice = $investmentIncludedModel->totalCurrentPrice;
        $totalPurchasePrice = $investmentIncludedModel->totalPurchasePrice;
        $totalReinertrag = $investmentIncludedModel->totalReinertrag;
        $totalRendite = $investmentIncludedModel->totalRendite;
        $timestamp = $investmentIncludedModel->timestamp;

        if ($totalCurrentPrice === 0)
        {
            $this->logger->error("Fehler beim Aktualisieren der Investmentsentwicklung bei $type: Fehler beim Abfragen.", ["app" => Application::APP_ID]);

            NotificationService::createNotification(User::USER, "Fehler beim Aktualisieren der Investmentsentwicklung bei $type: Fehler beim Abfragen.");

            throw new \Exception("Fehler beim Abfragen.");
        }

        $this->investmentsService->addInvestmentDevelopment(
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