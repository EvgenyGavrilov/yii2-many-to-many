<?php

namespace tests\models;

use \yii\db\ActiveRecord;

class User extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            \EvgenyGavrilov\behavior\ManyToManyBehavior::className(),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGroups()
    {
        return $this->hasMany(Group::className(), ['id' => 'group_id'])->viaTable('user_group', ['user_id' => 'id']);
    }
}
