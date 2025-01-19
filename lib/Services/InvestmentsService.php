<?php

namespace OCA\Investments\Services;

use OCA\Investments\Repositories\InvestmentsRepository;
use OCA\Shared\Services\UserFilesService;


class InvestmentsService
{
    private $investmentsRepository;


    public function __construct(UserFilesService $userFilesService)
    {
        $this->investmentsRepository = new InvestmentsRepository($userFilesService);
    }


    public function addInvestmentDevelopment(float $currentPrice, float $purchasePrice, float $reinertrag, float $rendite, \DateTime $timestamp, string $typeId)
    {
        $this->investmentsRepository->addInvestmentDevelopment($currentPrice, $purchasePrice, $reinertrag, $rendite, $timestamp, $typeId);
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
}