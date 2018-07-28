<?php
/**
 * bootstrap.php
 *
 * Загрузчик некоторых классов проекта для юнит тестов
 *
 * Created by PhpStorm.
 * @date 27.07.18
 * @time 17:19
 */

spl_autoload_register(function ($cls) {
    $class = dirname(__FILE__) . '/../task3/src/';
    $class .= str_replace(['\\', 'Task3/'], ['/', ''], $cls);
    $class .= '.php';
    if (file_exists($class)) {
        include_once($class);
    }
});