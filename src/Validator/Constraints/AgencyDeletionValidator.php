<?php

namespace App\Validator\Constraints;

use App\Entity\Agency;
use App\Validator\Constraints\AgencyDeletion;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Valide qu'une agence ne peut pas être supprimée si elle est utilisée par un ou plusieurs trajets
 */
class AgencyDeletionValidator extends ConstraintValidator
{

    /**
     * Vérifie si l'agence peut être supprimée
     * 
     * @param $agency
     * @param Constraint $contraint
     * 
     * @return void
     */
    public function validate($agency, Constraint $constraint): void
    {
        if (!$constraint instanceof AgencyDeletion) {
            return;
        }

        if (!$agency instanceof Agency) {
            return;
        }

        $hasDeparture = count($agency->getDepartureJourneys()) > 0;
        $hasArrival = count($agency->getArrivalJourneys()) > 0;

        if ($hasDeparture || $hasArrival) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }

}