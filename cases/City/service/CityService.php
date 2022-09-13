<?php

declare(strict_types=1);

namespace app\cases\City\service;

use app\cases\City\City;
use app\cases\City\dto\CityDto;
use app\cases\City\dto\CityFormDto;
use RuntimeException;
use Throwable;
use yii\db\StaleObjectException;

final class CityService
{
    /**
     * @param CityFormDto $formDto
     * @return CityDto
     * @throws RuntimeException
     */
    public function create(CityFormDto $formDto): CityDto
    {
        return $this->save(new City(), ['title' => $formDto->title]);
    }

    /**
     * @param int $id
     * @param CityFormDto $formDto
     * @return CityDto
     * @throws RuntimeException
     */
    public function update(int $id, CityFormDto $formDto): CityDto
    {
        return $this->save($this->getOne(['id' => $id]), ['title' => $formDto->title]);
    }

    /**
     * @param int $id
     * @return void
     * @throws RuntimeException
     * @throws Throwable
     * @throws StaleObjectException
     */
    public function delete(int $id): void
    {
        $model = $this->getOne(['id' => $id]);
        $model->delete();
    }

    private function save(City $model, array $attributes): CityDto
    {
        $model->setAttributes($attributes);

        if ($model->save() === false) {
            /** @todo: описать shared ошибки. */
            throw new RuntimeException("Ошибка записи данных: {%s}" . implode('; ', $model->getErrors()));
        }

        $model->refresh();

        return $model->toDto();
    }

    /**
     * @param array $condition
     * @return City
     * @throws RuntimeException
     */
    private function getOne(array $condition): City
    {
        $model = City::findOne($condition);
        if ($model === null) {
            throw new RuntimeException(
                sprintf('Not Found. [%s] City not found.', implode(':', $condition)),
                404
            );
        }

        return $model;
    }
}
