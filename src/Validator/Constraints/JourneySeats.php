<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use App\Validator\Constraints\JourneySeatsValidator;

#[\Attribute]
class JourneySeats extends Constraint
{
    public string $message = 'Le nombre de places disponibles ne peut pas être supérieur au nombre de places total';

    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }

    public function validatedBy(): string
    {
        return JourneySeatsValidator::class;
    }
}