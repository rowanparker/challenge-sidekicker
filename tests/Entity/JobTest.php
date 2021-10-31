<?php

namespace App\Tests\Entity;

use App\Entity\Job;
use Symfony\Component\String\ByteString;

class JobTest extends AbstractEntityTestCase
{
    public function test_title_notNull()
    {
        $job = new Job();
        $errors = $this->validate($job);
        $this->assertContains('Title can not be empty', $errors);
    }

    public function test_title_notEmptyString()
    {
        $job = new Job();
        $job->setTitle('');
        $errors = $this->validate($job);
        $this->assertContains('Title can not be empty', $errors);
    }

    public function test_title_maxLength()
    {
        $job = new Job();
        $job->setTitle(ByteString::fromRandom(101)->toString());
        $errors = $this->validate($job);
        $this->assertContains('Title can not exceed 100 characters', $errors);
    }

    public function test_description_notNull()
    {
        $job = new Job();
        $errors = $this->validate($job);
        $this->assertContains('Description can not be empty', $errors);
    }

    public function test_description_notEmptyString()
    {
        $job = new Job();
        $job->setDescription('');
        $errors = $this->validate($job);
        $this->assertContains('Description can not be empty', $errors);
    }

    public function test_description_maxLength()
    {
        $job = new Job();
        $job->setDescription(ByteString::fromRandom(501)->toString());
        $errors = $this->validate($job);

        $this->assertContains('Description can not exceed 500 characters', $errors);
    }

    public function test_date_notNull()
    {
        $job = new Job();
        $errors = $this->validate($job);
        $this->assertContains('Date can not be empty', $errors);
    }

    public function test_location_notNull()
    {
        $job = new Job();
        $job->setLocation('Adelaide');
        $errors = $this->validate($job);
        $this->assertContains('Location must be Sydney, Melbourne, Brisbane or Perth', $errors);
    }

    public function test_location_validSelection()
    {
        $job = new Job();
        $errors = $this->validate($job);
        $this->assertContains('Location must be Sydney, Melbourne, Brisbane or Perth', $errors);
    }
}