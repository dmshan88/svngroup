<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $name
 * @property string $group_id
 * @property string $fullname
 * @property string $password
 *
 * @property Group $group
 */
class User extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'group_id', 'fullname', 'password'], 'required'],
            [['name', 'group_id'], 'string', 'max' => 10],
            [['name', 'group_id'], 'string', 'min' => 3],
            ['name', 'match', 'pattern' => '[\w]'],
            [['fullname', 'password'], 'string', 'max' => 45],
            [['name'], 'unique'],
            [['fullname'], 'unique'],
            [['group_id'], 'exist', 'skipOnError' => true, 'targetClass' => Group::className(), 'targetAttribute' => ['group_id' => 'id']],
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
            'group_id' => 'Group ID',
            'fullname' => 'Fullname',
            'password' => 'Password',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGroup()
    {
        return $this->hasOne(Group::className(), ['id' => 'group_id']);
    }
}
