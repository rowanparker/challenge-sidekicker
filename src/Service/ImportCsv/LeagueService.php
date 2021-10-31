<?php

namespace App\Service\ImportCsv;

use Exception;
use League\Csv\Reader;

class LeagueService extends AbstractImportService
{
    /**
     * @throws Exception
     */
    public function recordsFromFile(string $filePath): ?array
    {
        if ( ! file_exists($filePath)) {
            throw new Exception(sprintf('Import file does not exist: %s', $filePath));
        }

        $records = [];

        $csv = Reader::createFromPath($filePath, 'r');
        $csv->setHeaderOffset(0);

        foreach ($csv->getRecords() as $record) {
            $records[] = $record;
        }

        return $records;
    }
}