<?php

namespace App\Validator\Constraints;

use App\Entity\Journey;
use App\Validator\Constraints\JourneySeats;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Valide que le nombre de sièges disponibles d'un trajet n'excède pas le nombre total de sièges
 */
class JourneySeatsValidator extends ConstraintValidator
{
    /**
     * Vérifie que le nombre de sièges disponibles ne dépasse pas le total
     * 
     * @param Journey $journey
     * @param Constraint $constraint
     * 
     * @return void
     */
    public function validate($journey, Constraint $constraint)
    {
        if (!$constraint instanceof JourneySeats) {
            return;
        }
        
        if (!$journey instanceof Journey) {
            return;
        }

        // Il faudra éventuellement adapter la règle ci-dessous si on compte le siège du conducteur dans le nombre total de sièges
        // Dans ce cas il faudra remplacer le ">" par un ">="

        if ($journey->getAvailableSeats() > $journey->getTotalSeats() ) {
            $constraint = $constraint;
            $this->context->buildViolation($constraint->message)
                ->atPath('availableSeats')
                ->addViolation();
        }
    }
}