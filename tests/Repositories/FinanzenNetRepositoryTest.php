<?php
/*
namespace OCA\Investments\Tests\Repositories;

require_once "/var/www/html/lib/base.php";

use OCA\Investments\Repositories\FinanzenNetRepository;
*/

interface IFinanzenRepository
{
    public function parse(string $link): float;
}

class FinanzenNetRepository implements IFinanzenRepository
{
    public function parse(string $link): float
    {
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $link);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);  // Antwort wird zurückgegeben statt ausgegeben

curl_setopt($curl, CURLOPT_COOKIEJAR, tempnam(sys_get_temp_dir(), 'cookies'));
curl_setopt($curl, CURLOPT_COOKIEFILE, tempnam(sys_get_temp_dir(), 'cookies'));
curl_setopt($curl, CURLOPT_REFERER, 'https://www.google.com/');
curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($curl, CURLOPT_TIMEOUT, 30);
curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36');
curl_setopt($curl, CURLOPT_HTTPHEADER, [
    'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
    'Accept-Language: de-DE,de;q=0.8,en-US;q=0.5,en;q=0.3',
    'Accept-Encoding: gzip, deflate',
    'Connection: keep-alive',
    'Upgrade-Insecure-Requests: 1',
]);

        $responseBody = curl_exec($curl);

        curl_close($curl);

        if (curl_errno($curl))
        {
            throw new \Exception("Fehler beim Abrufen der URL: " . $link);
        }

        $doc = new \DOMDocument();

       @$doc->loadHTML($responseBody);  // @ -> Warnungen unterdrücken

        print_r($responseBody);

        $xpath = new \DOMXPath($doc);

        $elements = $xpath->query('//span[@class="snapshot__values"]/span[@class="snapshot__value-current realtime-push"][following-sibling::*//span[@class="snapshot__value-unit" and text()="EUR"]]/span[@class="snapshot__value"]');

        if ($elements->length === 0)
        {
            throw new \Exception("Kein Preis gefunden in der Antwort von finanzen.net");
        }

        $betrag = str_replace([".", ","], ["", "."], $elements[0]->nodeValue);

        return (float) $betrag;
    }
}

class FinanzenNetRepositoryTest
{
    private $finanzenNetRepository;

    public function __construct()
    {
        $this->finanzenNetRepository = new FinanzenNetRepository();

        $this->parse("https://www.finanzen.net/aktien/apple-aktie");
        $this->parse("https://www.finanzen.net/devisen/ethereum-euro-kurs");
        $this->parse("https://www.finanzen.net/etf/hsbc-msci-world-etf-ie00b4x9l533");
        $this->parse("https://www.finanzen.net/rohstoffe/goldpreis");
    }


    private function parse(string $link)
    {
        $result = $this->finanzenNetRepository->parse($link);

        var_dump($result);
    }
}


new FinanzenNetRepositoryTest();