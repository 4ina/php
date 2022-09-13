<?php

declare(strict_types=1);

namespace app\cases\City\form;

use app\cases\City\dto\CityFormDto;
use yii\base\Model;

final class CityForm extends Model
{
    public ?string $title = null;

    public function rules(): array
    {
        return [
            [['title'], 'required'],
            [['title'], 'string', 'max' => 256],
        ];
    }

    public function toDto(): CityFormDto
    {
        return new CityFormDto($this->title);
    }
}
