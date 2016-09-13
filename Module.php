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
    public $defaultRoute = 'browse';

}
