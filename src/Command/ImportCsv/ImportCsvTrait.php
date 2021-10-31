<?php

namespace App\Command\ImportCsv;

use App\Entity\Applicant;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

trait ImportCsvTrait
{
    private function pathImportFile(): string
    {
        return $this->projectDir . '/import/jobs-list.csv';
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title($this->getDescription());

        try {
            $messages = $this->importService->process($this->pathImportFile());
            foreach ($messages as $message) {
                $io->info($message);
            }
        } catch (Exception $e) {
            $io->error($e->getMessage());
            return Command::FAILURE;
        }

        $io->success('File imported');
        return Command::SUCCESS;
    }
}