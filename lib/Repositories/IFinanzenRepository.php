<?php

namespace OCA\Investments\Repositories;


interface IFinanzenRepository
{
    public function parse(string $link): float;
}