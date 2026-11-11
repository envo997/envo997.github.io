<?php

namespace App\Command;

use App\Service\WeatherUtil;
use Symfony\Component\Console\Attribute\Argument;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'weather:city',
    description: 'Shows weather forecast for a country and city',
)]
final class WeatherCityCommand
{
    public function __construct(
        private readonly WeatherUtil $weatherUtil,
    ) {
    }

    public function __invoke(
        SymfonyStyle $io,
        #[Argument('Country Code')] string $countryCode,
        #[Argument('City')] string $city,
    ): int {
        $measurements = $this->weatherUtil->getWeatherForCountryAndCity($countryCode, $city);

        $io->title('Weather in ' . $city . ', ' . $countryCode);

        $rows = [];
        foreach ($measurements as $measurement) {
            $rows[] = [
                $measurement->getDate()->format('Y-m-d'),
                $measurement->getCelsius(),
            ];
        }

        $io->table(['Date', 'Temperature'], $rows);

        return Command::SUCCESS;
    }
}
