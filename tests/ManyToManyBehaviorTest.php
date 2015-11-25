<?php

namespace tests;

use tests\models\User;

class ManyToManyBehaviorTest extends \PHPUnit_Framework_TestCase
{

    public function setUp()
    {
        \Yii::$app->db
            ->createCommand(file_get_contents(__DIR__ . '/db/' . \Yii::$app->params['fileDump']))
            ->execute();
    }


    public function testInsertAndUpdateSimpleData()
    {
        $user = User::findOne(1);
        $db = $user->getDb();

        try {
            $user->setRelated('fail', [1]);
            $user->save();
            $this->fail("Relation 'fail' don't exist");
        } catch (\Exception $e) {
            $this->assertTrue(true);
        }

        $user->setRelated('groups', [2]);
        $user->save();
        $count = $db->createCommand('SELECT COUNT(*) FROM user_group WHERE user_id = ' . $user->id)->queryScalar();
        $this->assertEquals(1, $count);

        $user->setRelated('groups', [2, 3]);
        $user->save();
        $count = $db->createCommand('SELECT COUNT(*) FROM user_group WHERE user_id = ' . $user->id)->queryScalar();
        $this->assertEquals(2, $count);

        $user->setRelated('groups', [2]);
        $user->save();
        $count = $db->createCommand('SELECT COUNT(*) FROM user_group WHERE user_id = ' . $user->id)->queryScalar();
        $this->assertEquals(2, $count);

        $user->setRelated('groups', [2], true);
        $user->save();
        $count = $db->createCommand('SELECT COUNT(*) FROM user_group WHERE user_id = ' . $user->id)->queryScalar();
        $this->assertEquals(1, $count);

        $user->setRelated('groups', []);
        $user->save();
        $count = $db->createCommand('SELECT COUNT(*) FROM user_group WHERE user_id = ' . $user->id)->queryScalar();
        $this->assertEquals(1, $count);

        $user->setRelated('groups', [], true);
        $user->save();
        $count = $db->createCommand('SELECT COUNT(*) FROM user_group WHERE user_id = ' . $user->id)->queryScalar();
        $this->assertEquals(0, $count);
    }
}
