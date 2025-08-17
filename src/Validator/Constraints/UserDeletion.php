<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class UserDeletion extends Constraint
{
    public $message = 'Impossible de modifier ou supprimer cet utilisateur : il est associé à un ou plusieurs trajets';

    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
}