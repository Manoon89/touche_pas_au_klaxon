<?php

namespace App\Validator\Constraints;

use App\Entity\Journey;
use App\Validator\Constraints\JourneyDates;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class JourneyDatesValidator extends ConstraintValidator
{
    public function validate($journey, Constraint $constraint)
    {
        if (!$constraint instanceof JourneyDates) {
            return;
        }

        if (!$journey instanceof Journey) {
            return;
        }

        $today = new \DateTimeImmutable('today');

        if ($journey->getDepartureDate() < $today) {
            $constraint = $constraint;
            $this->context->buildViolation($constraint->departureMessage)
                ->atPath('departureDate')
                ->addViolation();
        }

        if ($journey->getArrivalDate() < $journey->getDepartureDate()) {
            $constraint = $constraint;
            $this->context->buildViolation($constraint->arrivalMessage)
                ->atPath('arrivalDate')
                ->addViolation();
        }

    }
}