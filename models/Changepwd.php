<?php

namespace app\models;

use Yii;


class Changepwd extends \yii\base\Model
{
    public $name;
    public $old_password;
    public $new_password;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'old_password', 'new_password'], 'required'],
            [['name',], 'string', 'max' => 10],
            [['new_password', 'old_password'], 'string', 'max' => 45],
           
        ];
    }


}
