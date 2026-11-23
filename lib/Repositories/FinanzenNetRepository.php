<?php

namespace OCA\Investments\Repositories;

use OCA\Investments\Repositories\IFinanzenRepository;
use OCP\Http\Client\IClientService;


class FinanzenNetRepository implements IFinanzenRepository
{
    private IClientService $clientService;

    public function __construct(IClientService $clientService)
    {
        $this->clientService = $clientService;
    }

    public function parse(string $link): float
    {
        $client = $this->clientService->newClient();

        $response = $client->get($link);

        $responseBody = $response->getBody();

        $doc = new \DOMDocument();

       @$doc->loadHTML($responseBody);  // @ -> Warnungen unterdrücken

        $xpath = new \DOMXPath($doc);

        $elements = $xpath->query('//span[contains(@class,"snapshot__value-current")][span[@class="snapshot__value-unit"][normalize-space()="EUR"]]/span[@class="snapshot__value"]');

        if ($elements->length === 0)
        {
            throw new \Exception("Kein Preis gefunden in der Antwort von finanzen.net");
        }

        $betrag = str_replace([".", ","], ["", "."], $elements->item(0)->textContent);

        return (float) $betrag;
    }
}