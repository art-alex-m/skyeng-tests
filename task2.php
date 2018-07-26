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
        /// Существующий файл не может быть прочитан
        /// Обработка ошибок в соотвествии с принятой концепцией
        /// Запись в лог, Exception
    } else {
        $data = file_get_contents($counterFile);
        if (is_numeric($data)) {
            $counter = (int)$data;
        } else {
            /// Данные в файле счетчика не могут быть преобразованы в чистовой тип
            /// Обработка ошибок в соотвествии с принятой концепцией
            /// Запись в лог, Exception
        }
    }
}

if (file_exists($counterFile) && !is_writable($counterFile)) {
    /// Существующий файл не может быть изменен
    /// Обработка ошибок в соотвествии с принятой концепцией
    /// Запись в лог, Exception
} elseif (is_writable(dirname($counterFile))) {
    $counter++;
    file_put_contents($counterFile, $counter);
} else {
    /// Отсутствует возможность создания файла в требуемой дирректории
    /// Обработка ошибок в соотвествии с принятой концепцией
    /// Запись в лог, Exception
}
