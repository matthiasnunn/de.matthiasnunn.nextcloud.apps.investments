<?php

namespace OCA\Investments\Services;

use OCA\Investments\Repositories\IFinanzenRepository;


class FinanzenService
{
    private IFinanzenRepository $finanzenRepository;

    public function __construct(IFinanzenRepository $finanzenRepository)
    {
        $this->finanzenRepository = $finanzenRepository;
    }

    public function getCurrentCourse($link): float
    {
        return $this->finanzenRepository->parse($link);
    }
}