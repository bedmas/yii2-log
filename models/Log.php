<?php

namespace sylletka\log\models;

use Yii;
use yii\helpers\BaseArrayHelper;
use sylletka\log\Module;

/**
 * This is the model class for table "log".
 *
 * @property string $id
 * @property int $level
 * @property string $category
 * @property int $log_time
 * @property string $prefix
 * @property string $message
 */
class Log extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */    
    public static function getDb()
    {
        $module = Module::getInstance();
        $db = $module->db;
        return Yii::$app->{$db};  
    }
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'log';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['level', 'log_time'], 'integer'],
            [['prefix', 'message'], 'string'],
            [['category'], 'string', 'max' => 64],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'level' => Yii::t('app', 'Level'),
            'category' => Yii::t('app', 'Category'),
            'log_time' => Yii::t('app', 'Log Time'),
            'prefix' => Yii::t('app', 'Prefix'),
            'message' => Yii::t('app', 'Message'),
        ];
    }

}
