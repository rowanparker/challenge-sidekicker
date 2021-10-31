<?php

namespace App\Tests\Entity;

use App\Entity\Applicant;

class ApplicantTest extends AbstractEntityTestCase
{
    public function test_name_notNull()
    {
        $applicant = new Applicant();
        $errors = $this->validate($applicant);
        $this->assertContains('Name can not be empty', $errors);
    }

    public function test_name_notEmptyString()
    {
        $applicant = new Applicant();
        $applicant->setName('');
        $errors = $this->validate($applicant);
        $this->assertContains('Name can not be empty', $errors);
    }
}