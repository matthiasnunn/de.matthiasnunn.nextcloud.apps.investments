<?php

namespace OCA\Investments\Repositories;

use OCA\Investments\Repositories\IFinanzenRepository;


class Finanzen100DeRepository implements IFinanzenRepository
{
    public function parse(string $link): float
    {
        $responseBody = file_get_contents($link);

        if ($responseBody === false)
        {
            throw new \Exception("Fehler beim Abrufen der URL: " . $link);
        }

        if (preg_match('/<strong class="quote__price__price">([0-9 , .]*)<\/strong>/', $responseBody, $matches))
        {
            return (float) str_replace([".", ","], ["", "."], $matches[1]);
        }

        $price = $this->parseAstroIsland($responseBody);

        if ($price === null)
        {
            throw new \Exception("Kein Preis gefunden in der Antwort von finanzen100.de");
        }

        return $price;
    }

    private function parseAstroIsland(string $responseBody): ?float
    {
        $doc = new \DOMDocument();
        @$doc->loadHTML($responseBody);  // decodiert &quot; automatisch

        $xpath = new \DOMXPath($doc);
        $nodes = $xpath->query('//astro-island[@component-export="default"][contains(@props,"initialSnapshot")]/@props');

        foreach ($nodes as $node)
        {
            $props = json_decode($node->nodeValue, true);

            $quote = $props['componentProps'][1]['initialSnapshot'][1]['QUOTE'][1] ?? null;

            if ($quote === null)
            {
                continue;
            }

            $unit  = $quote['UNIT'][1] ?? null;
            $price = $quote['PRICE_R'][1] ?? null;

            if ($unit === "EUR" && is_numeric($price))
            {
                return (float) $price;
            }
        }

        return null;
    }
}