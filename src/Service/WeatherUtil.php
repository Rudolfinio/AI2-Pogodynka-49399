<?php
declare(strict_types=1);

namespace App\Service;

use App\Entity\City;
use App\Entity\Weather;
use App\Repository\CityRepository;
use App\Repository\WeatherRepository;

class WeatherUtil
{
    public function __construct(
    private readonly CityRepository $cityRepository,
    private readonly WeatherRepository $weatherRepository,
    )
    {
    }
    /**
     * @return Weather[]
     */
    public function getWeatherForLocation(City $city): array
    {
        $measurements = $this->weatherRepository->findByCity($city);
        return $measurements;
    }

    /**
     * @return Weather[]
     */
    public function getWeatherForCountryAndCity(string $countryCode, string $city): array
    {
        $location = $this->cityRepository->findOneBy([
            'country_code' => $countryCode,
            'name' => $city,
        ]);

        $measurements = $this->getWeatherForLocation($location);

        return $measurements;
    }
}
