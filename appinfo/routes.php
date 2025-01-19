<?php

return
[
    "routes" =>
    [
//      [
//          "name" => "<Controller>#<Methode>"
//      ],
        [
            "name" => "main#index",
            "url" => "/",
            "verb" => "GET"
        ],
        [
            "name" => "main#investmentAktien",
            "url" => "/investment/aktien",
            "verb" => "GET"
        ],
        [
            "name" => "main#investmentDevisen",
            "url" => "/investment/devisen",
            "verb" => "GET"
        ],
        [
            "name" => "main#investmentETFs",
            "url" => "/investment/etfs",
            "verb" => "GET"
        ],
        [
            "name" => "main#investmentRohstoffe",
            "url" => "/investment/rohstoffe",
            "verb" => "GET"
        ],
        [
            "name" => "main#uebersicht",
            "url" => "/uebersicht",
            "verb" => "GET"
        ]
    ]
];