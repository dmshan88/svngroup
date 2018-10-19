<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "group".
 *
 * @property string $id
 * @property string $name
 *
 * @property Groupuser[] $groupusers
 * @property User[] $users
 */
class Group extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'group';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'name'], 'required'],
            ['id', 'match', 'pattern' => '[\w]'],
            [['id'], 'string', 'max' => 10],
            [['id'], 'string', 'min' => 3],
            [['name'], 'string', 'max' => 45],
            [['name'], 'unique'],
            [['id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGroupusers()
    {
        return $this->hasMany(Groupuser::className(), ['group_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['id' => 'user_id'])->viaTable('groupuser', ['group_id' => 'id']);
    }
}
