<?php

namespace App\Validator\Constraints;

use App\Entity\Journey;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class JourneySeatsValidator extends ConstraintValidator
{
    public function validate($journey, Constraint $constraint)
    {
        if (!$journey instanceof Journey) {
            return;
        }

        // next rule is to be adapt if we include the driver's seat in "TotalSeats" : replace ">" by ">="

        if ($journey->getAvailableSeats() > $journey->getTotalSeats() ) {
            $this->context->buildViolation($constraint->message)
                ->atPath('availableSeats')
                ->addViolation();
        }
    }
}