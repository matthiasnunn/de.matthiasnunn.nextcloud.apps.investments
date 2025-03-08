<?php

namespace OCA\Investments\Services;

use OCA\Investments\Repositories\InvestmentsRepository;


class InvestmentsDevelopmentService
{
    private InvestmentsRepository $investmentsRepository;


    public function __construct(InvestmentsRepository $investmentsRepository)
    {
        $this->investmentsRepository = $investmentsRepository;
    }


    public function addInvestmentDevelopment(float $currentPrice, float $purchasePrice, float $reinertrag, float $rendite, \DateTime $timestamp, int $typeId): void
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