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


    public function addInvestmentDevelopment(float $currentPrice, float $purchasePrice, float $reinertrag, float $rendite, \DateTime $timestamp, int $typeId): void
    {
        $this->investmentsRepository->addInvestmentDevelopment($currentPrice, $purchasePrice, $reinertrag, $rendite, $timestamp, $typeId);
    }


    public function getInvestmentsByTypeId(int $typeId): InvestmentResponseModel
    {
        $type = $this->investmentsRepository->getInvestmentsByTypeId($typeId);

        $investments = array_map(fn($investment) => $investment, $type->investments);

        foreach ($investments as $investment)
        {
//          $pid = pcntl_fork();

//          if ($pid === 0)
//          {
                $this->addInfos($investment);

//              exit(0);
//          }
        }

//      while (pcntl_waitpid(0, $status) !== -1)
//      {
            // Warten, bis alle Prozesse abgeschlossen sind.
//      }

        $investmentResponseModel = new InvestmentResponseModel();
        $investmentResponseModel->data = $investments;
        $investmentResponseModel->investmentIncludedModel = $this->calculateTotal($investments);

        return $investmentResponseModel;
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


    private function addInfos($investment): void
    {
        $investment->currentCourse = FinanzenService::getCurrentCourse($investment->link);

        foreach ($investment->purchases as $purchase)
        {
            $purchase->rendite = $this->calculateRendite($investment->currentCourse, $purchase->purchaseCourse);
            $purchase->currentPrice = $this->calculateCurrentPrice($purchase->purchasePrice, $purchase->rendite);
            $purchase->reinertrag = $this->calculateReinertrag($purchase->currentPrice, $purchase->purchasePrice);
        }
    }


    // Hinweis
    // -------
    // Kapital:    10.000€
    // Ertrag:     12.000€
    // Reinertrag:  2.000€
    // Rendite:        20%


    private function calculateCurrentPrice(float $purchasePrice, float $rendite): float
    {
        $currentPrice = $purchasePrice + (($purchasePrice / 100) * $rendite);

        return round($currentPrice, 2);
    }


    private function calculateReinertrag(float $currentPrice, float $purchasePrice): float
    {
        $reinertrag = $currentPrice - $purchasePrice;

        return round($reinertrag, 2);
    }


    private function calculateRendite(float $currentCourse, float $purchaseCourse): float
    {
        $rendite = (($currentCourse - $purchaseCourse) / $purchaseCourse) * 100;

        return round($rendite, 2);
    }


    private function calculateTotal(array $investments): InvestmentIncludedModel
    {
        $totalCurrentPrice  = 0;
        $totalPurchasePrice = 0;
        $totalReinertrag    = 0;

        foreach ($investments as $investment)
        {
            foreach ($investment->purchases as $purchase)
            {
                $totalCurrentPrice  += $purchase->currentPrice;
                $totalPurchasePrice += $purchase->purchasePrice;
                $totalReinertrag    += $purchase->reinertrag;
            }
        }

        $totalRendite = ($totalReinertrag * 100) / $totalPurchasePrice;

        $totalCurrentPrice  = round($totalCurrentPrice, 2);
        $totalPurchasePrice = round($totalPurchasePrice, 2);
        $totalReinertrag    = round($totalReinertrag, 2);
        $totalRendite       = round($totalRendite, 2);

        $investmentIncludedModel = new InvestmentIncludedModel();
        $investmentIncludedModel->timestamp = new \DateTime();
        $investmentIncludedModel->totalCurrentPrice = $totalCurrentPrice;
        $investmentIncludedModel->totalPurchasePrice = $totalPurchasePrice;
        $investmentIncludedModel->totalReinertrag = $totalReinertrag;
        $investmentIncludedModel->totalRendite = $totalRendite;

        return $investmentIncludedModel;
    }
}


class InvestmentResponseModel
{
    public array $data;
    public InvestmentIncludedModel $investmentIncludedModel;
}


class InvestmentModel
{
    public float $currentCourse;
}


class InvestmentPurchaseModel
{
    public float $currentPrice;
    public float $reinertrag;
    public float $rendite;
}


class InvestmentIncludedModel
{
    public \DateTime $timestamp;
    public float $totalCurrentPrice;
    public float $totalPurchasePrice;
    public float $totalReinertrag;
    public float $totalRendite;
}