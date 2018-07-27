<?php
/**
 * ServiceProviderInterface.php
 *
 * Created by PhpStorm.
 * @date 27.07.18
 * @time 15:30
 */

namespace Task3\Integration;

/**
 * Interface ServiceProviderInterface
 *
 * Интерфес общего функционала для поставщиков данных от сторонних сервисов
 *
 * @package Task3\Integration
 */
interface ServiceProviderInterface
{
    /**
     * Производит запрос с указанными параметрами на адрес стороннего сервиса
     *
     * @param array $queryParams Параметры для построения запроса к сервису
     * @return array Ответ сервиса
     * [
     *    'isRelevant' => <bool>, /// полезные данные или нет, ответ сервиса 200
     *    'data' => <str> /// данные ответа сервиса
     * ]
     */
    public function get(array $queryParams): array;

    /**
     * Проверяет имеет ли последний ответ сервиса ценность
     * В общем стучае это проверка на HTTP статус 200 - сервис отдал корректные данные
     *
     * @return bool true есть ценность, false - ценности нет
     */
    public function isRelevant() : bool;
}