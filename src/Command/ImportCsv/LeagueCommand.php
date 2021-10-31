<?php

namespace App\Command\ImportCsv;

use App\Service\ImportCsv\LeagueService;
use Exception;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:import-csv:league',
    description: 'Imports /import/data-file.csv using the league/csv package',
)]
class LeagueCommand extends Command
{
    use ImportCsvTrait;

    public function __construct(
        private string $projectDir,
        private LeagueService $importService,
    ) {
        parent::__construct();
    }
}
