<?php

namespace OCA\Investments\Repositories;

use OCA\Investments\Repositories\IFinanzenRepository;


class FinanzenNetRepository implements IFinanzenRepository
{
    public function parse(string $link): float
    {
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $link);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);  // Antwort wird zurückgegeben statt ausgegeben

        $responseBody = curl_exec($curl);

        curl_close($curl);

        if (curl_errno($curl))
        {
            throw new \Exception("Fehler beim Abrufen der URL: " . $link);
        }

        $doc = new \DOMDocument();

       @$doc->loadHTML($responseBody);  // @ -> Warnungen unterdrücken

        $xpath = new \DOMXPath($doc);

        $elements = $xpath->query('//span[span[@class="snapshot__value-unit" and text()="EUR"]]/span[@class="snapshot__value"]');

        if (empty($elements))
        {
            throw new \Exception("Kein Preis gefunden in der Antwort von finanzen.net");
        }

        $betrag = str_replace([".", ","], ["", "."], $elements[0]->nodeValue);

        return (float) $betrag;
    }
}