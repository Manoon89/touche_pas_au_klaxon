<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use App\Validator\Constraints\JourneyDatesValidator;

#[\Attribute]

class JourneyDates extends Constraint
{
    public $departureMessage = "La date de départ doit être supérieure ou égale à aujourd'hui";
    public $arrivalMessage = "La date d'arrivée doit être supérieure à la date de départ";

    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }

    public function validatedBy(): string
    {
        return JourneyDatesValidator::class;
    }

}