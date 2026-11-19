<?php

namespace App\Controller;

use App\Entity\Measurement;
use App\Service\WeatherUtil;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;

final class WeatherApiController extends AbstractController
{
    #[Route('/api/v1/weather', name: 'app_weather_api', methods: ['GET'])]
    public function index(
        #[MapQueryParameter] string $country,
        #[MapQueryParameter] string $city,
        WeatherUtil $weatherUtil,
        #[MapQueryParameter] string $format = 'json',
        #[MapQueryParameter('twig')] bool $twig = false,
    ): Response {
        $measurements = $weatherUtil->getWeatherForCountryAndCity($country, $city);

        if ($format !== 'json' && $format !== 'csv') {
            return new Response('Unsupported format', 400);
        }

        if ($twig) {
            $response = $this->render('weather_api/index.' . $format . '.twig', [
                'city' => $city,
                'country' => $country,
                'measurements' => $measurements,
            ]);

            $response->headers->set(
                'Content-Type',
                $format === 'json'
                    ? 'application/json'
                    : 'text/plain; charset=UTF-8'
            );

            return $response;
        }

        if ($format === 'csv') {
            $csv = "city,country,date,celsius,fahrenheit\n";

            foreach ($measurements as $m) {
                $csv .= implode(',', [
                        $city,
                        $country,
                        $m->getDate()->format('Y-m-d'),
                        $m->getCelsius(),
                        $m->getFahrenheit(),
                    ]) . "\n";
            }

            return new Response($csv, 200, [
                'Content-Type' => 'text/plain; charset=UTF-8',
            ]);
        }

        return $this->json([
            'city' => $city,
            'country' => $country,
            'measurements' => array_map(fn(Measurement $m) => [
                'date' => $m->getDate()->format('Y-m-d'),
                'celsius' => $m->getCelsius(),
                'fahrenheit' => $m->getFahrenheit(),
            ], $measurements),
        ]);
    }
}
