# yii2-log
This module provides a simple way to view the log entries stored in database via yii\log\DbTarget

Database driven log for yii2 projects
This module adds pages to browse the log events

##Install

1. configure at least one log target as a DbTarget

```php
        ...
        'log' => [
            ...
            'targets' => [
                [
                    'class' => 'yii\log\DbTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
            ...
        ],
        ...

```
Please refer to [The definitive guide about Yii 2, in the chapter about logging](http://www.yiiframework.com/doc-2.0/guide-runtime-logging.html) and to [the reference documentation about the class DbTarget](http://www.yiiframework.com/doc-2.0/yii-log-dbtarget.html) to get further information about logging and logging storage in database with Yii2.


2. enable the module in config file

```php
<?php
    ......
    'modules' => [
        'log' => [
            'class' => 'sylletka\log\Module',
        ],
    ],
    ......
```

##Usage

point to /log to browse the Log entries

