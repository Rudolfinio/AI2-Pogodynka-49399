<?php

namespace App\Controller;

use App\Entity\Weather;
use App\Service\WeatherUtil;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Annotation\Route;

class WeatherApiController extends AbstractController
{
    #[Route('/api/v1/weather', name: 'app_weather_api')]
    public function index(
        #[MapQueryParameter('country')] string $country,
        #[MapQueryParameter('city')] string $city,
        #[MapQueryParameter('format')] string $format = 'json',
        #[MapQueryParameter('twig')] bool $twig = false,
        WeatherUtil $weatherUtil,
    ): Response
    {

        $weathers = $weatherUtil->getWeatherForCountryAndCity($country, $city);


        if($format==='json') {
            if($twig == true){
                return $this->render('weather_api/index.json.twig', [
                    'city' => $city,
                    'country' => $country,
                    'weathers' => $weathers,
                ]);
            }else {
                return $this->json([
                    'city' => $city,
                    'country' => $country,
                    'measurements' => array_map(fn(Weather $m) => [
                        'date' => $m->getDate()->format('Y-m-d'),
                        'celsius' => $m->getCelsius(),
                        'fahrenheit' => $m->getFahrehneit(),
                    ], $weathers),
                ]);
            }
        }
        elseif ($format ==='csv'){
            if($twig==true){

                return $this->render('weather_api/index.csv.twig', [
                    'city' => $city,
                    'country' => $country,
                    'weathers' => $weathers,
                ]);
            }
            else {
                $csv = "city,country,date,celsius\n";
                $csv .= implode(
                    "\n",
                    array_map(fn(Weather $weather) => sprintf(
                        '%s,%s,%s,%s,%s',
                        $city,
                        $country,
                        $weather->getDate()->format('Y-m-d'),
                        $weather->getCelsius(),
                        $weather->getFahrenheit()
                    ), $weathers)
                );

                return new Response($csv, 200, [
//                    'Content-Type' => 'text/csv',
                ]);
            }

        }
        else{
            return $this->json([
                'error' => "wrong format",

            ]);
        }
    }
}
