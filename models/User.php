<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $name
 * @property string $fullname
 * @property string $password
 * @property string $department
 *
 * @property Groupuser[] $groupusers
 * @property Group[] $groups
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
            [['name', 'fullname', 'password', 'department'], 'required'],
            [['name', 'department'], 'string', 'max' => 10],
            [['fullname', 'password'], 'string', 'max' => 45],
            [['name'], 'string', 'min' => 3],
            [['name'], 'unique'],
            ['name', 'match', 'pattern' => '[\w]'],
            [['fullname'], 'unique'],
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
            'fullname' => 'Fullname',
            'password' => 'Password',
            'department' => 'Department',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGroupusers()
    {
        return $this->hasMany(Groupuser::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGroups()
    {
        return $this->hasMany(Group::className(), ['id' => 'group_id'])->viaTable('groupuser', ['user_id' => 'id']);
    }
}
