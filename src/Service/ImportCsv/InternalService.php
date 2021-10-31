<?php

namespace App\Service\ImportCsv;

use Exception;

class InternalService extends AbstractImportService
{
    /**
     * This internal CSV service reads the whole file as a string (as opposed to streaming the file)
     * @throws Exception
     */
    protected function recordsFromFile(string $filePath): ?array
    {
        if ( ! file_exists($filePath)) {
            throw new Exception(sprintf('Import file does not exist: %s', $filePath));
        }

        // Get record lines
        $raw = file_get_contents($filePath);
        $lines = explode("\n", $raw);

        // Assume first row is header, so exit if less than two entries (i.e. 1 data row) in the file
        if ( ! is_array($lines) || count($lines) < 2) {
            return [];
        }

        $records = [];

        // Assume always comma delimited (no tabs, no semi-colons)
        for ($i = 1; $i < count($lines); $i++) {

            // Remove trailing carriage return (CR)
            $line = trim($lines[$i]);

            // Assuming column order is fixed, we limit the explode method to 4
            // so that we don't have to worry about the commas in the applicant column
            $columns = explode(',', $line, 4);

            if ( ! is_array($columns) || count($columns) !== 4) {
                throw new Exception('Malformed CSV data on row: %d', $i);
            }

            // Add the data to the records array
            // Use the known CSV headers as index
            // Simple str_replace to remove double quotes if they exist in applicants
            // Would need something more robust if names with punctuation were used e.g. O'Connor
            $records[] = [
                'job title'       => $columns[0],
                'job description' => $columns[1],
                'date'            => $columns[2],
                'applicants'      => str_replace('"','',$columns[3]),
            ];

        }

        return $records;
    }
}