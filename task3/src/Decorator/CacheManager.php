<?php
/**
 * CacheManager.php
 *
 * Created by PhpStorm.
 * @date 27.07.18
 * @time 16:23
 */

namespace Task3\Decorator;

use DateTime;
use Exception;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Log\LoggerInterface;
use Task3\Integration\DataProvider;
use Task3\Integration\ServiceProviderInterface;

/**
 * Class CacheManager
 *
 * Класс, реализующий прослойку кеширования данных при запросах к сторонним сервисам
 *
 * ```php
 * $logger = new Logger(...);
 * $cache = new Cache(...);
 * $provider = new CacheManager(new IstewardDataProvider(), $cache, $logger);
 * $response = $provider->getResponse(['events', 'gte' => 0]);
 *
 * print_r($response);
 *
 * ```
 * @package Task3\Decorator
 */
class CacheManager
{
    /** @var CacheItemPoolInterface Компонент кеширования данных */
    protected $cache;
    /** @var LoggerInterface Компонент логгирования */
    protected $logger;
    /** @var ServiceProviderInterface Компонент для обращения к стороннему сервису */
    protected $provider;

    /**
     * CacheManager конструктор
     *
     * @param ServiceProviderInterface $provider Провайдер данных стороннего сервиса
     * @param CacheItemPoolInterface $cache Компонент кеширования
     * @param LoggerInterface $logger Компонент логирования
     */
    public function __construct(
        ServiceProviderInterface $provider,
        CacheItemPoolInterface $cache,
        LoggerInterface $logger
    ) {
        $this->cache = $cache;
        $this->logger = $logger;
        $this->provider = $provider;
    }

    /**
     * Возвращает ответ стороннего сервиса
     *
     * NOTE: Релевантный ответ сервиса кешируется на период 1 дня. Релвантность определяется по
     * ответу провайдера данных [[Task3\Integration\ServiceProviderInterface::isRelevant()]]
     *
     * @param array $queryParams Параметры запроса в соответсвии с конкретной
     * реализацией [[Task3\Integration\ServiceProviderInterface]]
     * @return array Структура ответа в соответствии с ServiceProviderInterface
     * @see ServiceProviderInterface::get()
     */
    public function getResponse(array $queryParams)
    {
        try {
            $cacheKey = $this->getCacheKey($queryParams);
            $cacheItem = $this->cache->getItem($cacheKey);
            if ($cacheItem->isHit()) {
                return $cacheItem->get();
            }

            $result = $this->provider->get($queryParams);

            if ($this->provider->isRelevant()) {
                $cacheItem
                    ->set($result)
                    ->expiresAt(
                        (new DateTime())->modify('+1 day')
                    );
                return $result;
            }
        } catch (Exception $e) {
            $this->logger->critical($e->getMessage());
        }

        return [];
    }

    /**
     * Создает ключ кеширования данных
     * @param mixed $queryParams
     * @return string
     */
    protected function getCacheKey($queryParams)
    {
        return json_encode($queryParams);
    }
}