<?php

namespace OCA\Investments\Tests\Repositories;

require_once "/var/www/html/lib/base.php";

use OCA\Investments\Repositories\FinanzenNetRepository;


class FinanzenNetRepositoryTest
{
    public function __construct()
    {
        $this->parse("https://www.finanzen.net/aktien/apple-aktie");
        $this->parse("https://www.finanzen.net/devisen/ethereum-euro-kurs");
        $this->parse("https://www.finanzen.net/etf/hsbc-msci-world-etf-ie00b4x9l533");
        $this->parse("https://www.finanzen.net/rohstoffe/goldpreis");
    }


    private function parse(string $link)
    {
        $result = FinanzenNetRepository::parse($link);

        var_dump($result);
    }
}


new FinanzenNetRepositoryTest();