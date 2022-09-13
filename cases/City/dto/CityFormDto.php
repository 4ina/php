<?php

declare(strict_types=1);

namespace app\cases\City\dto;

use app\cases\City\City;

final class CityFormDto
{
    public string $title;

    public function __construct(string $title)
    {
        $this->title = $title;
    }
}
