<?php

declare(strict_types=1);

namespace app\cases\City;

use yii\db\ActiveQuery;


/**
 * @psalm-suppress ImplementedReturnTypeMismatch
 * @method City|null one($db = null)
 * @method City[] all($db = null)
 * @method City[] each($batchSize = 100, $db = null)
 * @psalm-suppress PropertyNotSetInConstructor
 */
final class CityQuery extends ActiveQuery
{
    public function whereTitleLike(string $title): self
    {
        $this->andWhere(['like', 'title', $title]);

        return $this;
    }
}
