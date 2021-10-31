<?php

namespace App\Command\ImportCsv;

use App\Service\ImportCsv\InternalService;
use Exception;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:import-csv:internal',
    description: 'Imports /import/data-file.csv using the internal CSV parser',
)]
class InternalCommand extends Command
{
    use ImportCsvTrait;

    public function __construct(
        private string $projectDir,
        private InternalService $importService,
    ) {
        parent::__construct();
    }
}
