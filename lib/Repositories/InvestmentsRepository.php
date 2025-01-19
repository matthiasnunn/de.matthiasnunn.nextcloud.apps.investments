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