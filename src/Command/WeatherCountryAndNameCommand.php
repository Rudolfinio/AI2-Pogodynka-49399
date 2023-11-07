<?php

namespace App\Command;

use App\Repository\CityRepository;
use App\Service\WeatherUtil;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'weather:countryAndName',
    description: 'Add a short description for your command',
)]
class WeatherCountryAndNameCommand extends Command
{
    public function __construct(  private readonly CityRepository $cityRepository,
                                  private readonly WeatherUtil $weatherUtil,
                                  string $name = null,)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('code', InputArgument::REQUIRED, 'Code')
            ->addArgument('name', InputArgument::REQUIRED, 'name')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $country_code = $input->getArgument('code');
        $name = $input->getArgument('name');

        $location = $this->cityRepository->findOneBy([
            'country_code' => $country_code,
            'name' => $name,
        ]);

        $measurements = $this->weatherUtil->getWeatherForLocation($location);
        $io->writeln(sprintf('Location: %s', $location->getName()));
        foreach ($measurements as $measurement) {
            $io->writeln(sprintf("\t%s: %s",
                $measurement->getDate()->format('Y-m-d'),
                $measurement->getCelsius()
            ));
        }

        return Command::SUCCESS;
    }
}
