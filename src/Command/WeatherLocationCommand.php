<?php

namespace App\Command;

use App\Repository\LocationRepository;
use App\Service\WeatherUtil;
use Symfony\Component\Console\Attribute\Argument;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'weather:location',
    description: 'Shows weather forecast for a location',
)]
final class WeatherLocationCommand
{
    public function __construct(
        private readonly LocationRepository $locationRepository,
        private readonly WeatherUtil $weatherUtil,
    ) {
    }

    public function __invoke(
        SymfonyStyle $io,
        #[Argument('Location ID')] int $id,
    ): int {
        $location = $this->locationRepository->find($id);

        if ($location === null) {
            $io->error('Location not found');
            return Command::FAILURE;
        }

        $measurements = $this->weatherUtil->getWeatherForLocation($location);

        $io->title('Weather in ' . $location->getCity() . ', ' . $location->getCountry());

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
