<?php

namespace App\Controller;

use App\Entity\City;
use App\Repository\WeatherRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class   WeatherController extends AbstractController
{
    //#[Route('/weather/{code}/{name}', name: 'app_weather', requirements: ['code'=>'\S{2}','name'=>'\S*'])] //inny sposób
    #[Route('/weather/{name}&{code}', name: 'app_weather', requirements: ['code'=>'\S{2}','name'=>'\S*'])]
    public function city(City $city, WeatherRepository $repository): Response
    {

        $measurements = $repository->findByCity($city);

        return $this->render('weather/city.html.twig', [
            'city' => $city,
            'measurements' => $measurements,
        ]);
    }
}
