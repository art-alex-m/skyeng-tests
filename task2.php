<?php
/**
 * task2.php
 *
 * Описание решения Задания 2:
 * Реализовать счетчик вызова скрипта. Было принято решение, хранить данные в файле.
 *
 * Created by PhpStorm.
 * @date 26.07.18
 * @time 11:17
 */

$counter = 0;
$counterFile = './counter.txt';
if (file_exists($counterFile)) {
    if (!is_readable($counterFile)) {
        /// Обработка ошибок в соотвествии с принятой концепцией
        /// Запись в лог, Exception
    } else {
        $data = file_get_contents($counterFile);
        if (is_numeric($data)) {
            $counter = (int)$data;
        } else {
            /// Обработка ошибок в соотвествии с принятой концепцией
            /// Запись в лог, Exception
        }
    }
}

if (file_exists($counterFile) && !is_writable($counterFile)) {
    /// Обработка ошибок в соотвествии с принятой концепцией
    /// Запись в лог, Exception
} else {
    $counter++;
    file_put_contents($counterFile, $counter);
}
