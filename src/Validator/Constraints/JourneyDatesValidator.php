<?php

namespace App\Validator\Constraints;

use App\Entity\Journey;
use App\Validator\Constraints\JourneyDates;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Vérifie que les dates d'un trajet sont cohérentes : 
 * -> la date de départ ne peut pas être antérieure à la date du jour
 * -> la date d'arrivée ne peut pas être antérieure à la date de départ
 */
class JourneyDatesValidator extends ConstraintValidator
{
    /**
     * Vérifie la cohérence des dates d'un trajet
     * 
     * @param Journey $journey
     * @param Constraint $constraint
     * 
     * @return void
     */
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