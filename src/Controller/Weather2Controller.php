<?php

namespace App\Controller;

use App\Entity\City;
use App\Entity\Weather;
use App\Form\WeatherType;
use App\Repository\WeatherRepository;
use App\Service\WeatherUtil;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class Weather2Controller extends AbstractController
{


    #[Route('/weather/{name}&{code}', name: 'app_weather', requirements: ['code'=>'\S{2}','name'=>'\S*'])]
    public function city(
        #[MapEntity(mapping: ['name' => 'name', 'code' => 'country_code'])]
        City $location,
        WeatherUtil $util,
    ): Response
    {

        $measurements = $util->getWeatherForLocation($location);

        return $this->render('weather/city.html.twig', [
            'city' => $location,
            'measurements' => $measurements,
        ]);
    }
}