<?php

namespace OCA\Investments\Daos;

use OCA\Shared\Services\UserFilesService;


class InvestmentsDao
{
    private const DIR = "Investments";
    private const INVESTMENT_TYPE = "investment_type.json";
    private const INVESTMENT_TYPE_DEVELOPMENT = "investment_type_development.json";


    private $userFilesService;


    public function __construct(UserFilesService $userFilesService)
    {
        return $this->userFilesService = $userFilesService;
    }


    public function getInvestmentType(): array
    {
        return $this->get(self::INVESTMENT_TYPE, InvestmentType::class);
    }


    public function getInvestmentTypeDevelopment(): array
    {
        return $this->get(self::INVESTMENT_TYPE_DEVELOPMENT, InvestmentTypeDevelopment::class);
    }


    public function setInvestmentTypeDevelopment(array $investmentTypeDevelopments)
    {
        $json = json_encode($investmentTypeDevelopments, JSON_PRETTY_PRINT);

        $this->userFilesService->createOrUpdateFile(self::DIR, self::INVESTMENT_TYPE_DEVELOPMENT, $json);
    }


    private function get(string $file, string $class): array
    {
        $file = $this->userFilesService->getFile(self::DIR, $file);

        $json = json_decode($file, true);

        return array_map([$class, "fromFile"], $json);
    }
}


class InvestmentType
{
    public int $id;
    public string $name;

    public static function fromFile(array $json)
    {
        $investmentType = new InvestmentType();
        $investmentType->id = $json["id"];
        $investmentType->name = $json["name"];

        return $investmentType;
    }
}


class InvestmentTypeDevelopment implements \JsonSerializable
{
    public \DateTime $created;
    public float $currentPrice;
    public int $id;
    public float $purchasePrice;
    public float $reinertrag;
    public float $rendite;
    public \DateTime $timestamp;
    public int $typeId;

    public static function fromFile(array $json): InvestmentTypeDevelopment
    {
        $investmentTypeDevelopment = new InvestmentTypeDevelopment();
        $investmentTypeDevelopment->created = new \DateTime($json["created"]);
        $investmentTypeDevelopment->currentPrice = $json["current_price"];
        $investmentTypeDevelopment->id = $json["id"];
        $investmentTypeDevelopment->purchasePrice = $json["purchase_price"];
        $investmentTypeDevelopment->reinertrag = $json["reinertrag"];
        $investmentTypeDevelopment->rendite = $json["rendite"];
        $investmentTypeDevelopment->timestamp = new \DateTime($json["timestamp"]);
        $investmentTypeDevelopment->typeId = $json["type_id"];

        return $investmentTypeDevelopment;
    }

    public function jsonSerialize(): array  // Wird von `json_encode` aufgerufen
    {
        return
        [
            "created" => $this->created->format("Y-m-d\TH:i:sP"),
            "current_price" => $this->currentPrice,
            "id" => $this->id,
            "purchase_price" => $this->purchasePrice,
            "reinertrag" => $this->reinertrag,
            "rendite" => $this->rendite,
            "timestamp" => $this->timestamp->format("Y-m-d\TH:i:sP"),
            "type_id" => $this->typeId
        ];
    }
}