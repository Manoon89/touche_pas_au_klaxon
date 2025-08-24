<?php

namespace App\Validator\Constraints;

use App\Entity\Agency;
use App\Validator\Constraints\AgencyDeletion;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class AgencyDeletionValidator extends ConstraintValidator
{

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