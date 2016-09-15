# yii2-log
Database driven log for yii2 projects
This module adds pages to browse the log events

Install

1. configure at least one log target as a DbTarget

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

Usage

go to /log/browse to view the Log entries
