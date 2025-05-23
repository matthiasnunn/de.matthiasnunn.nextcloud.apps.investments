<?php

namespace OCA\Investments\Tests\Repositories;

require_once "/var/www/html/lib/base.php";

use OCA\Investments\Repositories\Finanzen100DeRepository;


class Finanzen100DeRepositoryTest
{
    private $finanzen100DeRepository;

    public function __construct()
    {
        $this->finanzen100DeRepository = new Finanzen100DeRepository();

        $this->parse("https://www.finanzen100.de/aktien/apple-wkn-865985_H1526430491_86627/");
        $this->parse("https://www.finanzen100.de/waehrungen/ethereum-eth-eur_H1951636285_190438971/");
        $this->parse("https://www.finanzen100.de/etf/hsbc-msci-world-ucits-etf-usd-dis_H2011342759_37516240/");
        $this->parse("https://www.finanzen100.de/edelmetalle/gold-in-euro_H449481463_20401474/");
    }


    private function parse(string $link)
    {
        $result = $this->finanzen100DeRepository->parse($link);

        var_dump($result);
    }
}


new Finanzen100DeRepositoryTest();