<?php

declare(strict_types=1);

namespace app\cases\City\dto;

use app\cases\City\City;

final class CityDto
{
    public int $id;
    public string $title;

    public function __construct(City $city)
    {
        $this->id = $city->id;
        $this->title = $city->title;
    }
}
