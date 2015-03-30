<?php

namespace tests\models;

use \yii\db\ActiveRecord;

class Group extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'group';
    }
}
