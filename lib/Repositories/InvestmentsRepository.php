<?php

namespace OCA\Investments\Repositories;

use OCA\Investments\Daos\InvestmentsDao;
use OCA\Investments\Daos\InvestmentTypeDevelopment;
use OCA\Shared\Services\UserFilesService;


class InvestmentsRepository
{
    private $investmentsDao;


    public function __construct(UserFilesService $userFilesService)
    {
        $this->investmentsDao = new InvestmentsDao($userFilesService);
    }


    public function addInvestmentDevelopment(float $currentPrice, float $purchasePrice, float $reinertrag, float $rendite, \DateTime $timestamp, string $typeId): void
    {
        $investmentTypeDevelopmentArr = $this->investmentsDao->getInvestmentTypeDevelopment();

        $investmentTypeDevelopment = new InvestmentTypeDevelopment();
        $investmentTypeDevelopment->created = new \DateTime("now", new \DateTimeZone("UTC"));
        $investmentTypeDevelopment->currentPrice = $currentPrice;
        $investmentTypeDevelopment->id = end($investmentTypeDevelopmentArr)->id + 1;
        $investmentTypeDevelopment->purchasePrice = $purchasePrice;
        $investmentTypeDevelopment->reinertrag = $reinertrag;
        $investmentTypeDevelopment->rendite = $rendite;
        $investmentTypeDevelopment->timestamp = $timestamp;
        $investmentTypeDevelopment->typeId = $typeId;

        $investmentTypeDevelopmentArr[] = $investmentTypeDevelopment;

        $this->investmentsDao->setInvestmentTypeDevelopment($investmentTypeDevelopmentArr);
    }


    public function getInvestmentsByTypeId(int $typeId): Type
    {
        $daoFees = $this->investmentsDao->getInvestmentPurchaseFee();
        $daoInvestments = $this->investmentsDao->getInvestment();
        $daoPurchases = $this->investmentsDao->getInvestmentPurchase();
        $daoTypes = $this->investmentsDao->getInvestmentType();
        $daoZuordnungen = $this->investmentsDao->getInvestmentPurchaseFeeZuordnung();

        foreach ($daoTypes as $daoType)
        {
            if ($daoType->id !== $typeId)
            {
                continue;
            }

            $investments = [];

            foreach ($daoInvestments as $daoInvestment)
            {
                if ($daoInvestment->typeId !== $daoType->id)
                {
                    continue;
                }

                $purchases = [];

                foreach ($daoPurchases as $daoPurchase)
                {
                    if ($daoPurchase->investmentId !== $daoInvestment->id)
                    {
                        continue;
                    }

                    $fees = [];

                    foreach ($daoZuordnungen as $daoZuordnung)
                    {
                        if ($daoZuordnung->purchaseId !== $daoPurchase->id)
                        {
                            continue;
                        }

                        $daoFee = $this->getFeeForFeeId($daoZuordnung->feeId, $daoFees);

                        $fee = new Fee();
                        $fee->fee = $daoFee->fee;
                        $fee->name = $daoFee->name;

                        $fees[] = $fee;
                    }

                    $purchase = new Purchase();
                    $purchase->fees = $fees;
                    $purchase->hinweise = $daoPurchase->hinweise;
                    $purchase->purchaseDate = $daoPurchase->purchaseDate;
                    $purchase->purchaseCourse = $daoPurchase->purchaseCourse;
                    $purchase->purchasePrice = $daoPurchase->purchasePrice;
                    $purchase->quantity = $daoPurchase->quantity;

                    $purchases[] = $purchase;
                }

                $investment = new Investment();
                $investment->link = $daoInvestment->linkFinanzenNet;
                $investment->name = $daoInvestment->name;
                $investment->purchases = $purchases;

                $investments[] = $investment;
            }

            $type = new Type();
            $type->investments = $investments;
            $type->name = $daoType->name;

            return $type;
        }
    }


    public function getInvestmentsDevelopment(): array
    {
        $investmentsDevelopment = [];

        $types = $this->investmentsDao->getInvestmentType();
        $developments = $this->investmentsDao->getInvestmentTypeDevelopment();

        foreach ($types as $type)
        {
            $developmentsForType = array_filter($developments, function($development) use ($type) {
                return $development->typeId === $type->id;
            });

            $investmentDevelopment = new InvestmentDevelopment();
            $investmentDevelopment->items = array_map([InvestmentDevelopmentItem::class, "fromDao"], $developmentsForType);
            $investmentDevelopment->name = $type->name;

            $investmentsDevelopment[] = $investmentDevelopment;
        }

        return $investmentsDevelopment;
    }


    private function getFeeForFeeId(int $feeId, array $fees)
    {
        $matchedFees = [];

        foreach ($fees as $fee)
        {
            if ($fee->id === $feeId)
            {
                $matchedFees[] = $fee;
            }
        }

        if (count($matchedFees) === 0)
        {
            throw new \Exception("No fee with feeId $feeId found!");
        }

        if (count($matchedFees) === 1)
        {
            return $matchedFees[0];
        }

        throw new \Exception("More than one fee with feeId $feeId found!");
    }
}


class InvestmentDevelopment
{
    public array $items;
    public string $name;
}


class InvestmentDevelopmentItem implements \JsonSerializable
{
    public float $currentPrice;
    public float $rendite;
    public \DateTime $timestamp;

    public static function fromDao($investmentType)
    {
        $investmentDevelopmentItem = new InvestmentDevelopmentItem();
        $investmentDevelopmentItem->currentPrice = $investmentType->currentPrice;
        $investmentDevelopmentItem->rendite = $investmentType->rendite;
        $investmentDevelopmentItem->timestamp = $investmentType->timestamp;

        return $investmentDevelopmentItem;
    }

    public function jsonSerialize(): array  // Wird von `json_encode` aufgerufen
    {
        return
        [
            "currentPrice" => $this->currentPrice,
            "rendite" => $this->rendite,
            "formattedDate" => $this->timestamp->format("d.m")
        ];
    }
}


class Type
{
    public array $investments;
    public string $name;
}


class Investment
{
    public string $link;
    public string $name;
    public array $purchases;
}


class Purchase
{
    public array $fees;
    public string|null $hinweise;
    public float $purchaseCourse;
    public \DateTime $purchaseDate;
    public float $purchasePrice;
    public string $quantity;
}


class Fee
{
    public float $fee;
    public string $name;
}