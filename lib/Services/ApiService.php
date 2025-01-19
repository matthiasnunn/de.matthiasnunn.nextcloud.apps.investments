<?php

namespace OCA\Investments\Services;

use OCP\Http\Client\IClientService;


class ApiService
{
    private static $URL = "https://api.matthiasnunn.de";

    private static $USER = "Skript";
    private static $PASSWORD = "ydjowaop";


    public static function getInvestmentsByType(IClientService $clientService, string $type): InvestmentResponseModel
    {
        $data = self::get($clientService, "/v2/investments?fields=calculated&typ=$type");

        $json = json_decode($data, true);

        return InvestmentResponseModel::fromApi($json);
    }


    private static function get(IClientService $clientService, string $path)
    {
        $client = $clientService->newClient();

        $response = $client->get(self::$URL . $path, [
            "headers" => array_merge(
                self::getAcceptJsonHeader(),
                self::getAuthorizationHeader()
            )
        ]);

        return $response->getBody();
    }


    // TODO: nötig?
    private static function getAcceptJsonHeader(): array
    {
        return [ "Accept" => "application/json" ];
    }


    private static function getAuthorizationHeader(): array
    {
        $auth = base64_encode(self::$USER . ":" . self::$PASSWORD);

        return [ "Authorization" => "Basic $auth" ];
    }


    private static function getContentTypeJsonHeader(): array
    {
        return [ "Content-Type" => "application/json" ];
    }
}


class InvestmentResponseModel
{
    public array $data;
    public InvestmentIncludedModel $investmentIncludedModel;

    public static function fromApi(array $json)
    {
        $investmentResponseModel = new InvestmentResponseModel();
        $investmentResponseModel->data = array_map([InvestmentModel::class, "fromApi"], $json["data"]);
        $investmentResponseModel->investmentIncludedModel = InvestmentIncludedModel::fromApi($json["included"][0]);

        return $investmentResponseModel;
    }
}


class InvestmentModel
{
    public float|null $currentCourse;  // TODO: nullable, wird aber bei diesem Aufruf immer mitgeschickt wird -> api design
    public string $link;
    public string $name;
    public array $purchases;
    public string $type;

    public static function fromApi(array $json)
    {
        $investmentModel = new InvestmentModel();
        $investmentModel->currentCourse = $json["current_course"];
        $investmentModel->link = $json["link"];
        $investmentModel->name = $json["name"];
        $investmentModel->purchases = array_map([InvestmentPurchaseModel::class, "fromApi"], $json["purchases"]);
        $investmentModel->type = $json["type"];

        return $investmentModel;
    }
}


class InvestmentPurchaseModel
{
    public float|null $currentPrice;  // TODO: nullable, wird aber bei diesem Aufruf immer mitgeschickt wird -> api design
    public array $fees;
    public string|null $hinweise;
    public \DateTime $purchase;
    public float $purchaseCourse;
    public float $purchasePrice;
    public string $quantity;
    public float|null $reinertrag;    // TODO: nullable, wird aber bei diesem Aufruf immer mitgeschickt wird -> api design
    public float|null $rendite;       // TODO: nullable, wird aber bei diesem Aufruf immer mitgeschickt wird -> api design

    public static function fromApi(array $json)
    {
        $investmentPurchaseModel = new InvestmentPurchaseModel();
        $investmentPurchaseModel->currentPrice = $json["current_price"];
        $investmentPurchaseModel->fees = array_map([InvestmentPurchaseFeeModel::class, "fromApi"], $json["fees"]);
        $investmentPurchaseModel->hinweise = $json["hinweise"];
        $investmentPurchaseModel->purchase = new \DateTime($json["purchase"]);
        $investmentPurchaseModel->purchaseCourse = $json["purchase_course"];
        $investmentPurchaseModel->purchasePrice = $json["purchase_price"];
        $investmentPurchaseModel->quantity = $json["quantity"];
        $investmentPurchaseModel->reinertrag = $json["reinertrag"];
        $investmentPurchaseModel->rendite = $json["rendite"];

        return $investmentPurchaseModel;
    }
}


class InvestmentPurchaseFeeModel
{
    public string $name;
    public float $fee;

    public static function fromApi(array $json)
    {
        $investmentPurchaseFeeModel = new InvestmentPurchaseFeeModel();
        $investmentPurchaseFeeModel->name = $json["name"];
        $investmentPurchaseFeeModel->fee = $json["fee"];

        return $investmentPurchaseFeeModel;
    }
}


class InvestmentIncludedModel
{
    public \DateTime $timestamp;
    public float $totalCurrentPrice;
    public float $totalPurchasePrice;
    public float $totalReinertrag;
    public float $totalRendite;

    public static function fromApi(array $json)
    {
        $investmentIncludedModel = new InvestmentIncludedModel();
        $investmentIncludedModel->timestamp = new \DateTime($json["timestamp"]);
        $investmentIncludedModel->totalCurrentPrice = $json["total_current_price"];
        $investmentIncludedModel->totalPurchasePrice = $json["total_purchase_price"];
        $investmentIncludedModel->totalReinertrag = $json["total_reinertrag"];
        $investmentIncludedModel->totalRendite = $json["total_rendite"];

        return $investmentIncludedModel;
    }
}