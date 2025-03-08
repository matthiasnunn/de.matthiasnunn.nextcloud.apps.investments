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

        preg_match('/<strong class="quote__price__price">[0-9 , .]*<\/strong>/', $responseBody, $matches);

        if (empty($matches))
        {
            throw new \Exception("Kein Preis gefunden in der Antwort von finanzen100.de");
        }

        preg_match('/([0-9.,]+)/', $matches[0], $betragMatch);

        if (empty($betragMatch))
        {
            throw new \Exception("Kein Zahlenwert gefunden in der Antwort von finanzen100.de");
        }

        $betrag = str_replace([".", ","], ["", "."], $betragMatch[0]);

        return (float) $betrag;
    }
}