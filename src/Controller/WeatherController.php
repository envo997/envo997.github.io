<?php

namespace App\Controller;

use App\Repository\LocationRepository;
use App\Service\WeatherUtil;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class WeatherController extends AbstractController
{
    #[Route('/weather/{country}/{city}', name: 'app_weather', requirements: ['country' => '[A-Za-z]{2}'])]
    public function city(
        string $country,
        string $city,
        LocationRepository $locationRepository,
        WeatherUtil $util,
    ): Response {
        $location = $locationRepository->findOneByCityAndCountry($city, $country);

        if (!$location) {
            throw $this->createNotFoundException('Location not found');
        }

        $measurements = $util->getWeatherForLocation($location);

        return $this->render('weather/city.html.twig', [
            'location' => $location,
            'measurements' => $measurements,
        ]);
    }
}
