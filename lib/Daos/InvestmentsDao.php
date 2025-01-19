<?php

namespace OCA\Investments\Daos;

use OCA\Shared\Services\UserFilesService;


class InvestmentsDao
{
    private const DIR = "Investments";
    private const INVESTMENT = "investment.json";
    private const INVESTMENT_PURCHASE = "investment_purchase.json";
    private const INVESTMENT_PURCHASE_FEE = "investment_purchase_fee.json";
    private const INVESTMENT_PURCHASE_FEE_ZUORDNUNG = "investment_purchase_fee_zuordnung.json";
    private const INVESTMENT_TYPE = "investment_type.json";
    private const INVESTMENT_TYPE_DEVELOPMENT = "investment_type_development.json";


    private $userFilesService;


    public function __construct(UserFilesService $userFilesService)
    {
        return $this->userFilesService = $userFilesService;
    }


    public function getInvestment(): array
    {
        return $this->get(self::INVESTMENT, Investment::class);
    }


    public function getInvestmentPurchase(): array
    {
        return $this->get(self::INVESTMENT_PURCHASE, InvestmentPurchase::class);
    }


    public function getInvestmentPurchaseFee(): array
    {
        return $this->get(self::INVESTMENT_PURCHASE_FEE, InvestmentPurchaseFee::class);
    }


    public function getInvestmentPurchaseFeeZuordnung(): array
    {
        return $this->get(self::INVESTMENT_PURCHASE_FEE_ZUORDNUNG, InvestmentPurchaseFeeZuordnung::class);
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


    private function get(string $fileName, string $class): array
    {
        $file = $this->userFilesService->getFile(self::DIR, $fileName);

        $json = json_decode($file, true);

        return array_map([$class, "fromFile"], $json);
    }
}


class Investment
{
    public int $id;
    public string|null $isin;
    public string $linkFinanzen100De;
    public string $linkFinanzenNet;
    public string $name;
    public string|null $symbol;
    public string|null $symbolYahooFinanzen;
    public int $typeId;
    public string|null $wkn;

    public static function fromFile(array $json)
    {
        $investment = new Investment();
        $investment->id = $json["id"];
        $investment->isin = $json["isin"];
        $investment->linkFinanzen100De = $json["link_finanzen100_de"];
        $investment->linkFinanzenNet = $json["link_finanzen_net"];
        $investment->name = $json["name"];
        $investment->symbol = $json["symbol"];
        $investment->symbolYahooFinanzen = $json["symbol_yahoo_finanzen"];
        $investment->typeId = $json["type_id"];
        $investment->wkn = $json["wkn"];

        return $investment;
    }
}


class InvestmentPurchase
{
    public \DateTime $created;
    public string|null $hinweise;
    public int $id;
    public int $investmentId;
    public \DateTime $lastUpdated;
    public float $purchaseCourse;
    public \DateTime $purchaseDate;
    public float $purchasePrice;
    public string $quantity;

    public static function fromFile(array $json)
    {
        $investmentPurchase = new InvestmentPurchase();
        $investmentPurchase->$created = new \DateTime($json["created"]);
        $investmentPurchase->hinweise = $json["hinweise"];
        $investmentPurchase->id = $json["id"];
        $investmentPurchase->investmentId = $json["investment_id"];
        $investmentPurchase->$lastUpdated = new \DateTime($json["last_updated"]);
        $investmentPurchase->purchaseCourse = $json["purchase_course"];
        $investmentPurchase->purchaseDate = new \DateTime($json["purchase_date"]);
        $investmentPurchase->purchasePrice = $json["purchase_price"];
        $investmentPurchase->quantity = $json["quantity"];

        return $investmentPurchase;
    }
}


class InvestmentPurchaseFee
{
    public float $fee;
    public int $id;
    public string $name;

    public static function fromFile(array $json)
    {
        $investmentPurchaseFee = new InvestmentPurchaseFee();
        $investmentPurchaseFee->fee = $json["fee"];
        $investmentPurchaseFee->id = $json["id"];
        $investmentPurchaseFee->name = $json["name"];

        return $investmentPurchaseFee;
    }
}


class InvestmentPurchaseFeeZuordnung
{
    public int $feeId;
    public int $id;
    public int $purchaseId;

    public static function fromFile(array $json)
    {
        $investmentPurchaseFeeZuordnung = new InvestmentPurchaseFeeZuordnung();
        $investmentPurchaseFeeZuordnung->feeId = $json["fee_id"];
        $investmentPurchaseFeeZuordnung->id = $json["id"];
        $investmentPurchaseFeeZuordnung->purchaseId = $json["purchase_id"];

        return $investmentPurchaseFeeZuordnung;
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