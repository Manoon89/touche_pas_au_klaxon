<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class AgencyDeletion extends Constraint
{
    public $message = 'Impossible de modifier ou supprimer cette agence : elle est utilisée par un ou plusieurs trajets';

    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
}