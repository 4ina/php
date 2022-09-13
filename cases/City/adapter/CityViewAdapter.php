<?php

declare(strict_types=1);

namespace app\cases\City\adapter;

use app\cases\City\dto\CityDto;
use app\cases\City\dto\CityViewDto;

final class CityViewAdapter
{
    private CityViewDto $viewDto;

    public function __construct(CityDto $dto)
    {
        $this->viewDto = new CityViewDto();
        $this->viewDto->title = $dto->title;
    }

    public function toDto(): CityViewDto
    {
        return $this->viewDto;
    }
}
