<?php
/**
 * BigNumber.php
 *
 * Класс для работы с большими числами
 *
 * Created by PhpStorm.
 * @date 26.07.18
 * @time 13:20
 */

/**
 * Class BigNumber
 * Класс для работы с большими числами
 */
class BigNumber
{
    /** @var string Исходное число */
    protected $original;
    /** @var string Перевернутое исходное число для расчетов */
    protected $reversed;

    /**
     * BigNumber конструктор
     * @param string $bigNumberAsString Исходное число ввиде строки
     */
    public function __construct($bigNumberAsString)
    {
        $this->original = (string) $bigNumberAsString;

        if ($this->getLength() > 1) {
            $this->original = ltrim($this->original, '0');
        }

        if ($this->original == '') {
            $this->original = '0';
        }

        $this->reversed = strrev($this->original);
    }

    /**
     * Геттер для [[$original]]
     * @return string
     * @see $original
     */
    public function getNumber()
    {
        return $this->original;
    }

    /**
     * Геттер для [[$reversed]]
     * @return string
     * @see $reversed
     */
    public function getReversed()
    {
        return $this->reversed;
    }

    /**
     * Возвращает количество символов в числе
     * @return int
     */
    public function getLength()
    {
        return strlen($this->original);
    }

    /**
     * Вывод на печать
     * @return string
     */
    public function __toString()
    {
        return $this->original;
    }

    /**
     * Сумма двух больших чисел
     * @param BigNumber $secondNum
     * @return BigNumber
     */
    public function sum(BigNumber $secondNum)
    {
        $firstLen = $this->getLength();
        $first = $this->getReversed();

        $second = $secondNum->getReversed();
        $secondLen = $secondNum->getLength();

        if ($firstLen < $secondLen) {
            $first = $second;
            $firstLen = $secondLen;
            $second = $this->getReversed();
            $secondLen = $this->getLength();
        }

        $mind = 0;
        $result = '';
        for ($i = 0; $i < $firstLen; $i++) {
            $digitA = (int) $first[$i];
            $digitB = 0;
            if ($i < $secondLen) {
                $digitB = (int) $second[$i];
            }
            $digitR = $digitA + $digitB + $mind;
            if ($digitR >= 10) {
                $digitR -= 10;
                $mind = 1;
            } else {
                $mind = 0;
            }
            $result .= (string) $digitR;
        }

        if ($mind > 0) {
            $result .= (string) $mind;
        }

        return new BigNumber(strrev($result));
    }
}