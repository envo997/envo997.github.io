<?php

namespace App\Tests\Entity;

use App\Entity\Measurement;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class MeasurementTest extends TestCase
{
    public static function dataGetFahrenheit(): array
    {
        return [
            ['0', '32'],
            ['-100', '-148'],
            ['100', '212'],
            ['0.5', '32.9'],
            ['10', '50'],
            ['20', '68'],
            ['25', '77'],
            ['-40', '-40'],
            ['37', '98.6'],
            ['-10', '14'],
        ];
    }

    #[DataProvider('dataGetFahrenheit')]
    public function testGetFahrenheit(
        string $celsius,
        string $expectedFahrenheit
    ): void {
        $measurement = new Measurement();
        $measurement->setCelsius($celsius);

        $this->assertEquals(
            (float) $expectedFahrenheit,
            $measurement->getFahrenheit()
        );
    }
}
