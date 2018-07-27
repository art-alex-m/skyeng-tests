<?php
/**
 * IstewardDataProvider.php
 *
 * Created by PhpStorm.
 * @date 27.07.18
 * @time 15:42
 */

namespace Task3\Integration;

/**
 * Class IstewardDataProvider
 *
 * Производит запросы к ресурсам сервиса Isteward http://api.isteward.ru
 *
 * @package Task3\Integration
 */
class IstewardDataProvider implements ServiceProviderInterface
{
    /** @var string URL стороннего сервиса */
    protected $apiUrl = 'https://api.isteward.ru';
    /** @var bool Указатель релевантности последнего запроса */
    protected $isResponseRelevant = false;

    /**
     * Производит запрос с указанными параметрами на адрес стороннего сервиса
     *
     * @param array $queryParams Параметры для построения запроса к сервису
     * [
     *    0 => URI, /// идентификатор пути запрашиваемого ресурса
     *    <str> => <str|int|array>, /// параметры запроса для функции http_build_query()
     *    <str> => <str|int|array>,
     *    ...
     * ]
     * @return array Ответ сервиса
     * [
     *    'isRelevant' => <bool>, /// полезные данные или нет, ответ сервиса 200
     *    'data' => <str> /// данные ответа сервиса
     * ]
     */
    public function get(array $queryParams): array
    {
        if (!isset($queryParams[0])) {
            throw new \InvalidArgumentException(
                'Должен быть передан URI ресурса в первом аргументе параметров');
        }
        $url = [$this->apiUrl, $queryParams[0]];
        unset($queryParams[0]);
        $params = http_build_query($queryParams);
        $query = implode('/', $url) . '?' . $params;
        $context = stream_context_create($this->getServiceContext());
        $this->isResponseRelevant = false;

        $response = '';
        if ($res = @fopen($query, 'r', false, $context)) {
            $response = stream_get_contents($res);
            $meta = stream_get_meta_data($res);
            fclose($res);
            $this->isResponseRelevant = $this->checkIsHttpOk($meta);
        }

        return [
            'isRelevant' => $this->isResponseRelevant,
            'data' => $response,
        ];
    }

    /**
     * @inheritdoc
     */
    public function isRelevant(): bool
    {
        return $this->isResponseRelevant;
    }

    /**
     * Возвращает конфигурацию для создания контекста запроса
     * @return array
     */
    protected function getServiceContext()
    {
        return [
            'http' => [
                'header' => "Accept: application/json\r\n",
            ],
        ];
    }

    /**
     * Проверяет был ли ответ сервиса 200
     * @param array $metaData Мета данные контекста полученные при помощи stream_get_meta_data()
     * @return bool
     */
    protected function checkIsHttpOk($metaData)
    {
        if (isset($metaData['wrapper_data'], $metaData['wrapper_data'][0])) {
            if (false !== strpos($metaData['wrapper_data'][0], '200')) {
                return true;
            }
        }
        return false;
    }
}