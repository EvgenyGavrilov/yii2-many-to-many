<?php

namespace EvgenyGavrilov\behavior;

use yii\base\Behavior;
use yii\base\UnknownPropertyException;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * Behavior of relation update in junction table
 *
 * `To update the related data`, the relation with the junction table should be described  in the model.
 *
 * Behavior doesn't use transaction. Use this method to do
 * [transactions()](http://www.yiiframework.com/doc-2.0/yii-db-activerecord.html#transactions%28%29-detail) in the model or other way.
 *
 * Usage
 * ------------
 *
 * ```php
 * class User extends ActiveRecord
 * {
 *      public function behaviors()
 *      {
 *          return [
 *              'crossTable' => [
 *                  'class' => 'common\behaviors\ManyToMany'
 *              ]
 *          ];
 *      }
 *
 *      public function getGroups()
 *      {
 *          return $this->hasMany(Group::className(), ['id' => 'group_id'])->viaTable('user_group', ['user_id' => 'id']);
 *      }
 * }
 *
 * $model = User::find(1);
 * // Add new or update relation
 * $model->setRelated('groups', [1]);
 * // or
 * $model->setRelated('groups', [1, 2]);
 * $model->save();
 *
 * // Add or update the data with the remove old relations
 * $model->setRelated('groups', [1, 2], true);
 * $model->save();
 *
 * // Delete all relations
 * $model->setRelated('groups', [], true);
 * $model->save();
 * ```
 *
 * @package common\behaviors
 * @author scorpion
 */
class ManyToManyBehavior extends Behavior
{
    /**
     * Object parent.
     * @var ActiveRecord
     */
    public $owner;

    /**
     * Collection of data on junction table
     * @var mixed[]
     */
    private $_related = [];

    /**
     * Delete all records that are not in the array or delete all if the array is empty
     * @param string $name
     */
    private function deleteOld($name)
    {
        $db = $this->owner->getDb();
        $primaryKeyValue = $this->owner->getPrimaryKey();
        $meta = $this->_related[$name]['meta'];
        $ids = $this->_related[$name]['ids'];
        $condition = "{$meta['foreignKey']} = $primaryKeyValue";
        if (!empty($ids)) {
            $ids = implode(',', $ids);
            $condition .= " AND {$meta['remoteKey']} NOT IN ({$ids})";
        }
        $db->createCommand()->delete($meta['tableName'], $condition)->execute();
    }

    /**
     * Check the existence of relation
     * @param string $name
     * @return bool
     */
    public function issetRelation($name)
    {
        $getter = 'get' . $name;
        return (method_exists($this->owner, $getter) && $this->owner->$getter() instanceof ActiveQuery);
    }

    /**
     * Get meta data of the junction table
     *
     * The array consists of three keys:
     *
     * 1. tableName - the name of the junction table
     * 2. foreignKey - name field, make a relation with the current model
     * 3. remoteKey - name field connecting two tables
     *
     * @param string $name
     * @return mixed[]
     */
    private function getRelationMeta($name)
    {
        $query = $this->owner->getRelation($name);
        $remoteKey = array_values($query->link);
        $remoteKey = reset($remoteKey);
        $foreignKey = array_keys($query->via->link);
        $foreignKey = reset($foreignKey);
        return [
            'foreignKey' => $foreignKey,
            'remoteKey' => $remoteKey,
            'tableName' => reset($query->via->from),
        ];
    }

    /**
     * @inheritdoc
     */
    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_INSERT => 'updateRelations',
            ActiveRecord::EVENT_AFTER_UPDATE => 'updateRelations',
        ];
    }

    /**
     * Add new data for the junction table
     *
     * If flag 'deleteOld' is set on the true, old relations are deleted.
     * If flag 'deleteOld' is set on the false, then only new relations are added
     *
     * @param string $name
     * @param int[] $ids
     * @param bool $deleteOld
     * @throws UnknownPropertyException
     */
    public function setRelated($name, array $ids, $deleteOld = false)
    {
        if (!$this->issetRelation($name)) {
            throw new UnknownPropertyException('Setting unknown property: ' . get_class($this->owner) . '::' . $name);
        }
        $this->_related[$name] = [
            'deleteOld' => $deleteOld,
            'ids' => $ids,
            'meta' => $this->getRelationMeta($name),
        ];
    }

    /**
     * Junction table update
     */
    public function updateRelations()
    {
        foreach ($this->_related as $nameRelation => $data) {
            if ($data['deleteOld']) {
                $this->deleteOld($nameRelation);
            }
            $db = $this->owner->getDb();
            $primaryKeyValue = $this->owner->getPrimaryKey();
            $meta = $data['meta'];
            foreach ($data['ids'] as $id) {
                $db->createCommand(
                    "INSERT IGNORE `{$meta['tableName']}` (`{$meta['foreignKey']}`, `{$meta['remoteKey']}`) VALUES ({$primaryKeyValue}, {$id});"
                )->execute();
            }
        }
    }
}
