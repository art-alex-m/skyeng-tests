<?php
/**
 * IstewardDataProviderTest.php
 *
 * Created by PhpStorm.
 * @date 27.07.18
 * @time 17:05
 */

use Task3\Integration\IstewardDataProvider;
use PHPUnit\Framework\TestCase;

/**
 * Class IstewardDataProviderTest
 *
 * Проверяет работоспособность реализации IstewardDataProvider
 */
class IstewardDataProviderTest extends TestCase
{
    /** @var IstewardDataProvider Экземпляр класса для тестов */
    protected $provider;

    /**
     * @inheritdoc
     */
    public function setUp()
    {
        $this->provider = new IstewardDataProvider();
    }

    /**
     * Проверяет отработку при инициации
     */
    public function testIsRelevant()
    {
        $this->assertFalse($this->provider->isRelevant());
    }

    /**
     * Проверяет корректность ответа функции при корректном запросе
     */
    public function testGet()
    {
        $response = $this->provider->get(['events', 'gte' => 0, 'per-page' => 2]);
        $this->assertCount(2, $response);
        $this->assertArrayHasKey('isRelevant', $response);
        $this->assertArrayHasKey('data', $response);
        $this->assertTrue(!empty($response['data']));
        $this->assertTrue($response['isRelevant']);
        print_r($response);
    }

    /**
     * Проверяет корректность ответа функции при некорректном запросе
     */
    public function testGetWithWrongQuery()
    {
        $response = $this->provider->get(['xesss']);
        $this->assertCount(2, $response);
        $this->assertArrayHasKey('isRelevant', $response);
        $this->assertArrayHasKey('data', $response);
        $this->assertFalse($response['isRelevant']);
        $this->assertEmpty($response['data']);
        $this->assertFalse($this->provider->isRelevant());
    }
}
