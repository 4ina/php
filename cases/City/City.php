<?php

declare(strict_types=1);

namespace app\cases\City;

use app\cases\City\dto\CityDto;
use Yii;
use yii\base\InvalidConfigException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property string $title
 * @property string $created_at
 * @property string $updated_at
 */
final class City extends ActiveRecord
{
    public static function tableName(): string
    {
        return '{{%cities}}';
    }

    public function rules(): array
    {
        return [
            [['title'], 'required'],
            [['title'], 'string'],
            [['created_at', 'updated_at'], 'datetime', 'format' => 'php:Y-m-d H:i:s'],
        ];
    }

    public function behaviors(): array
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    self::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    self::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
                'value' => date('Y-m-d H:i:s'),
            ],
        ];
    }

    /**
     * @throws InvalidConfigException
     */
    public static function find(): CityQuery
    {
        return Yii::createObject(CityQuery::class, [__CLASS__]);
    }

    public function toDto(): CityDto
    {
        return new CityDto($this);
    }
}
