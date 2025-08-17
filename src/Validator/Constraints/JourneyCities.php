<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use App\Validator\Constraints\JourneyCitiesValidator;

#[\Attribute]
class JourneyCities extends Constraint
{
    public string $message = 'La ville de départ et la ville d’arrivée doivent être différentes.';

    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }

    public function validatedBy(): string
    {
        return JourneyCitiesValidator::class;
    }
}