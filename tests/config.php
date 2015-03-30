<?php

$config = [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=test',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',
        ],
    ],
];
if (is_file(__DIR__ . '/config.local.php')) {
    include(__DIR__ . '/config.local.php');
}
return $config;