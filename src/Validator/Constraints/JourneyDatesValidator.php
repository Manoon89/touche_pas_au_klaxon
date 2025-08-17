<?php

namespace App\Validator\Constraints;

use App\Entity\Journey;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class JourneyDatesValidator extends ConstraintValidator
{
    public function validate($journey, Constraint $constraint)
    {
        if (!$journey instanceof Journey) {
            return;
        }

        $today = new \DateTimeImmutable('today');

        if ($journey->getDepartureDate() < $today) {
            $this->context->buildViolation($constraint->departureMessage)
                ->atPath('departureDate')
                ->addViolation();
        }

        if ($journey->getArrivalDate() < $journey->getDepartureDate()) {
            $this->context->buildViolation($constraint->arrivalMessage)
                ->atPath('arrivalDate')
                ->addViolation();
        }

    }
}