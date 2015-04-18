Yii2 many to many behavior
------------------------
[![Build Status](https://travis-ci.org/EvgenyGavrilov/yii2-many-to-many.svg?branch=master)](https://travis-ci.org/EvgenyGavrilov/yii2-many-to-many)
[![Packagist](https://img.shields.io/packagist/l/doctrine/orm.svg)](https://packagist.org/packages/evgeny-gavrilov/yii2-many-to-many)
[![Code Climate](https://codeclimate.com/github/EvgenyGavrilov/yii2-many-to-many/badges/gpa.svg)](https://codeclimate.com/github/EvgenyGavrilov/yii2-many-to-many)
[![Test Coverage](https://codeclimate.com/github/EvgenyGavrilov/yii2-many-to-many/badges/coverage.svg)](https://codeclimate.com/github/EvgenyGavrilov/yii2-many-to-many)

This behavior allows to update or delete relations in the junction table, which are described in the model with
[via()](http://www.yiiframework.com/doc-2.0/yii-db-activerelationtrait.html#via%28%29-detail)
or
[viaTable()](http://www.yiiframework.com/doc-2.0/yii-db-activequery.html#viaTable%28%29-detail).

Behavior doesn't use transaction. Use this method to do [transactions()](http://www.yiiframework.com/doc-2.0/yii-db-activerecord.html#transactions%28%29-detail)
in the model or other way.

# Install
```
php composer.phar require evgeny-gavrilov/yii2-many-to-many
```

# Usage

## Connection of behavior and description of the relation in the model
```php
class User extends ActiveRecord
{
    public function behaviors()
    {
        return [
            EvgenyGavrilov\behavior\ManyToManyBehavior::className()
        ];
    }
    
    public function getGroups()
    {
        return $this->hasMany(Group::className(), ['id' => 'group_id'])->viaTable('user_group', ['user_id' => 'id']);
    }
}
```

## Usage in the code
```php
$user = User::findOne(1);
// Add new or update relation
$model->setRelated('groups', [1]);
// or
$model->setRelated('groups', [1, 2]);
$model->save();

// Add or update the data with the remove old relations
$model->setRelated('groups', [1, 2], true);
$model->save();

// Delete all relations
$model->setRelated('groups', [], true);
$model->save();
```