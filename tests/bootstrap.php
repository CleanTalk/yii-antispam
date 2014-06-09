<?php

require_once(__DIR__ . '/../vendor/yiisoft/yii/framework/yiit.php');
require_once(__DIR__ . '/../vendor/autoload.php');

Yii::createWebApplication(
    array(
        'basePath' => __DIR__ . '/../',
        'import' => array(
            'application.*',
        ),
    )
);

define('CLEANTALK_TEST_API_KEY', 'This is not key!');
