<?php
/**
 * BigNumberTest.php
 *
 * Created by PhpStorm.
 * @date 26.07.18
 * @time 14:53
 */

include ('../BigNumber.php');

use PHPUnit\Framework\TestCase;

/**
 * Class BigNumberTest
 * Тестовый класс для проверки работоспособности BigNumber
 */
class BigNumberTest extends TestCase
{
    /**
     * Проверяет вывод в строку
     */
    public function test__toString()
    {
        $number = '123456';
        $num = new BigNumber($number);
        $this->assertEquals($number, (string)$num);
    }

    /**
     * Проверяет возвращение числа
     */
    public function testGetNumber()
    {
        $number = '123456';
        $num = new BigNumber($number);
        $this->assertEquals($number, $num->getNumber());
    }

    /**
     * Проверяет корректность вычисления суммы двух больших чисел
     * @param string $first
     * @param string $second
     * @param string $result
     * @dataProvider dataSumProvider
     */
    public function testSum($first, $second, $result)
    {
       $numberA = new BigNumber($first);
       $numberB = new BigNumber($second);
       $numberR = $numberA->sum($numberB);
       $this->assertInstanceOf(BigNumber::class, $numberR);
       $this->assertEquals($result, $numberR->getNumber());
    }

    public function testGetReversed()
    {
        $number = '123456';
        $num = new BigNumber($number);
        $this->assertEquals('654321', $num->getReversed());
    }

    /**
     * @dataProvider dataGetLengthProvider
     */
    public function testGetLength($first, $len)
    {
        $num = new BigNumber($first);
        $this->assertEquals($len, $num->getLength());
    }

    public function dataGetLengthProvider()
    {
        return [
            ['1', 1],
            ['0123', 3],
            ['123', 3],
            ['', 1],
        ];
    }

    /**
     * Данные для проверки суммирования в разных ситуациях
     * @return array
     */
    public function dataSumProvider()
    {
        return [
            ['123', '123', '246'],
            ['1234', '123', '1357'],
            ['123', '12345', '12468'],
            ['9', '9', '18'],
            ['0', '0', '0'],
            ['1', '0', '1'],
            ['1', '9999', '10000'],
            ['12345', '12345', '24690'],
            ['0123', '1', '124'],
        ];
    }
}
