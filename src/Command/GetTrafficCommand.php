<?php

namespace App\Command;

use App\Manager\TrafficJamManager;
use App\Provider\AnwbProvider;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class GetTrafficCommand extends Command
{
    protected static $defaultName = 'app:get:traffic';

    /** @var AnwbProvider */
    private $trafficJamProvider;
    /** @var TrafficJamManager */
    private $trafficJamManager;

    /**
     * GetTrafficCommand constructor.
     * @param AnwbProvider $trafficJamProvider
     * @param TrafficJamManager $trafficJamManager
     */
    public function __construct(AnwbProvider $trafficJamProvider, TrafficJamManager $trafficJamManager)
    {
        parent::__construct();
        $this->trafficJamProvider = $trafficJamProvider;
        $this->trafficJamManager = $trafficJamManager;
    }

    protected function configure(): void
    {
        $this->setDescription('Adds traffic information to database');
    }

    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $io = new SymfonyStyle($input, $output);
        $io->comment('Retrieving current traffic jam information');

        $trafficJams = [];
        try {
            $trafficJams = $this->trafficJamProvider->saveCurrentTrafficJams();
        } catch (GuzzleException $exception) {
            $io->error(sprintf('Error : %s', $exception->getMessage()));
        }

        $trafficJamOutPut = [];
        foreach ($trafficJams as $trafficJam) {
            $trafficJamOutPut[] = [$trafficJam->getFrom(), $trafficJam->getTo()];
        }

        $io->table(['From', 'To'], $trafficJamOutPut);
        $io->success('Successfully retrieved');
    }
}
