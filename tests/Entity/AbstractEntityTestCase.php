<?php

namespace App\Tests\Entity;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validation;

abstract class AbstractEntityTestCase extends KernelTestCase
{
    protected function validate(mixed $entity): array
    {
        $validator = Validation::createValidatorBuilder()
            ->enableAnnotationMapping(true)
            ->getValidator();

        $errors = [];

        foreach ($validator->validate($entity) as $violation) {
            /** @var ConstraintViolation $violation */
            $errors[] = $violation->getMessage();
        }

        return $errors;
    }
}