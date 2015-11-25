<?php

$config = [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=yii2_many_to_many',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',
        ],
    ],
    'params' => [
        'fileDump' => 'mysql.sql'
    ],
];
if (is_file(__DIR__ . '/config.local.php')) {
    $config = array_merge(
        $config,
        require(__DIR__ . '/config.local.php')
    );
}
return $config;