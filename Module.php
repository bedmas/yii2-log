<?php

namespace sylletka\log;

/**
 * log module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'sylletka\log\controllers';
    /**
     * @inheritdoc
     */   
    public $defaultRoute = 'browse';
    /**
     * @var integer the maximum lenght of the log messages displayed in index
     */
    public $messageLen = 200;

}
