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