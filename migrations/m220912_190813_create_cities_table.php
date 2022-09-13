<?php

/** @noinspection PhpIllegalPsrClassPathInspection */

declare(strict_types=1);

use yii\db\Migration;

final class m220912_190813_create_cities_table extends Migration
{
    private string $tableName = '{{%cities}}';

    public function safeUp(): bool
    {
        $this->createTable(
            $this->tableName,
            [
                'id' => $this->primaryKey(),
                'title' => $this->string()->notNull()->comment('Название города.'),
                'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
                'updated_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            ]
        );

        return true;
    }

    public function safeDown(): bool
    {
        $this->dropTable($this->tableName);

        return true;
    }
}
