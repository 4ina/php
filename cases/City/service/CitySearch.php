<?php

declare(strict_types=1);

namespace app\cases\City\service;

use app\cases\City\City;
use app\cases\City\CityQuery;
use app\cases\City\dto\CityDto;
use RuntimeException;
use yii\base\InvalidConfigException;
use yii\base\Model;

final class CitySearch extends Model
{
    public ?string $title = null;

    public function rules(): array
    {
        return [
            [['title'], 'string'],
        ];
    }

    /**
     * @param array $attributes
     * @return array
     * @throws InvalidConfigException
     */
    public function search(array $attributes): array
    {
        $query = City::find();
        $this->setAttributes($attributes);
        if ($this->validate() === false) {
            /** todo: error to class */
            $listErrors = [];
            foreach ($this->getErrors() as $attribute => $errors) {
                $listErrors[] = "[$attribute] " . implode('; ', $errors);
            }
            throw new RuntimeException(sprintf('Validation. [%s]', implode('; ', $listErrors)));
        }

        $this->prepareQuery($query);

        /** todo: dtoCollection pagination */
        return $query->all();
    }

    /**
     * @param int $id
     * @return CityDto
     * @throws RuntimeException
     */
    public function getById(int $id): CityDto
    {
        $model = City::findOne($id);
        if ($model === null) {
            throw new RuntimeException(sprintf('Not Found. [%s] City not found.', $id), 404);
        }

        return $model->toDto();
    }

    private function prepareQuery(CityQuery $query): void
    {
        if ($this->title !== null) {
            $query->whereTitleLike($this->title);
        }
    }
}
