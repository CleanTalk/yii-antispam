<?php

require_once(__DIR__ . '/../vendor/autoload.php');

defined('YII_ENABLE_EXCEPTION_HANDLER') or define('YII_ENABLE_EXCEPTION_HANDLER', false);
defined('YII_ENABLE_ERROR_HANDLER') or define('YII_ENABLE_ERROR_HANDLER', false);


require_once(__DIR__ . '/../vendor/yiisoft/yii/framework/yii.php');
Yii::$enableIncludePath = false;    // for other autoloaders

Yii::createWebApplication(
    array(
        'basePath' => __DIR__ . '/../',
        'import' => array(
            'application.*',
        ),
    )
);

define('CLEANTALK_TEST_API_KEY', 'This is not key, only unit testing!');
