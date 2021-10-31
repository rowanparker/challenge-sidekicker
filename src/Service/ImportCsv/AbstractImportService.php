<?php

namespace App\Service\ImportCsv;

use App\Entity\Applicant;
use App\Entity\Job;
use App\Entity\JobApplicant;
use Carbon\CarbonImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

abstract class AbstractImportService
{
    abstract protected function recordsFromFile(string $filePath): ?array;

    public function __construct(
        private EntityManagerInterface $entityManager

    ){}

    /**
     * @throws Exception
     */
    public function process(string $filePath): array
    {
        $messages = [];

        $records = $this->recordsFromFile($filePath);
        $messages[] = sprintf('Found %d records', count($records));

        // Create Applicants
        $applicants = $this->processApplicants($records);
        $messages[] = sprintf('Added %d applicants', count($applicants));

        // Create Jobs
        $jobs = $this->processJobs($records, $applicants);
        $messages[] = sprintf('Added %d jobs', count($jobs));

        return $messages;
    }

    /**
     * @param array $records
     * @param Applicant[] $applicants
     * @return array
     * @throws Exception
     */
    private function processJobs(array $records, array $applicants): array
    {
        $entities = [];

        // Create the Job entities, loop over the applicants and add the JobApplicant entity
        foreach ($records as $record) {
            $job = new Job();
            $job->setTitle($record['job title']);
            $job->setDescription($record['job description']);
            $job->setDate(CarbonImmutable::createFromFormat('d/m/Y',$record['date']));
            $job->setLocation($this->extractLocation($record['job description']));

            // Link with applicants
            foreach ($this->explodeApplicants($record['applicants']) as $applicantName) {

                // This should actually never occur, as the processApplicants method has already
                // been called and uses the same loop.
                if ( ! array_key_exists($applicantName, $applicants)) {
                    throw new Exception(sprintf('No applicant entity found for: %s', $applicantName));
                }

                $jobApplicant = new JobApplicant();
                $jobApplicant->setJob($job);
                $jobApplicant->setApplicant($applicants[$applicantName]);

                // Mark to be persisted
                $this->entityManager->persist($jobApplicant);

            }

            // Mark to be persisted
            $this->entityManager->persist($job);

            $entities[] = $entities;
        }

        // Save entities to database
        $this->entityManager->flush();

        return $entities;
    }

    private function processApplicants(array $records): array
    {
        $entities = [];

        foreach ($records as $record) {

            // Explode all applicants for this job
            foreach ($this->explodeApplicants($record['applicants']) as $applicantName) {

                // If there is already an entity created, move on
                if (array_key_exists($applicantName, $entities)) {
                    continue;
                }

                // Create entity and mark to be persisted
                $entities[$applicantName] = (new Applicant())->setName($applicantName);
                $this->entityManager->persist($entities[$applicantName]);
            }
        }

        // Save entities to database
        $this->entityManager->flush();

        return $entities;
    }

    /**
     * @throws Exception
     */
    private function extractLocation(string $description): string
    {
        // Assumes fixed list of sydney, melbourne, brisbane, perth
        // Assumes first occurrence in string is the location
        preg_match('/sydney|melbourne|perth|brisbane/i', $description, $matches);

        if ( ! isset($matches[0])) {
            throw new Exception(sprintf('No valid location value in string: %s', $description));
        }

        // Clean up value
        return ucfirst(strtolower($matches[0]));
    }

    private function explodeApplicants(string $applicants): array
    {
        // Assumes there is never a trailing comma, e.g.  "tom,luke,jane,"
        return array_map('trim', explode(',', $applicants));
    }
}