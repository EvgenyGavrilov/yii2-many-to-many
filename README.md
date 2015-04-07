Yii2 many to many behavior
------------------------

Этот behavior позволяет обновить или удалить связи многое ко многим, описанных в модели с помощью via() или viaTable().

This behavior allows update or delete related in the junction table, described in the model with
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
```php
class User extends ActiveRecord
{
    public function behaviors()
    {
        return [
            'crossTable' => [
                'class' => 'EvgenyGavrilov\behavior\ManyToManyBehavior'
            ]
        ];
    }
    
    public function getGroups()
    {
        return $this->hasMany(Group::className(), ['id' => 'group_id'])->viaTable('user_group', ['user_id' => 'id']);
    }
}

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