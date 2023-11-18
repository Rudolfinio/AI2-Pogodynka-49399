<?php

namespace App\Tests\Entity;

use App\Entity\Weather;
use PHPUnit\Framework\TestCase;

class WeatherTest extends TestCase
{

    public function dataGetFahrenheit(): array
    {
        return [
            ['0', 32],
            ['-100', -148],
            ['100', 212],
            ['0.5', 32.9],
            ['-32.4', -26.32],
            ['32.4', 90.32],
            ['-14.4', 6.08],
            ['14.4', 57.92],
            ['50.9', 123.62],
            ['-50.9', -59.62],
        ];
    }
    /**
     * @dataProvider dataGetFahrenheit
     */
    public function testGetFahrenheit($celsius, $expectedFahrenheit): void    {
        $weather = new Weather();

        $weather->setCelsius($celsius);
        $this->assertEquals($expectedFahrenheit, $weather->getFahrenheit());


//        $weather->setCelsius('0');
//        $this->assertEquals(32, $weather->getFahrenheit());
//
//        $weather->setCelsius('-100');
//        $this->assertEquals(-148, $weather->getFahrenheit());
//
//        $weather->setCelsius('100');
//        $this->assertEquals(212, $weather->getFahrenheit());
    }
}
