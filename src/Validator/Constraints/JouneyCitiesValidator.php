<?php

namespace App\Validator\Constraints;

use App\Entity\Journey;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class JourneyCitiesValidator extends ConstraintValidator
{
    public function validate($journey, Constraint $constraint)
    {
        if (!$journey instanceof Journey) {
            return;
        }

        $departure = $journey->getDepartureAgency();        
        $arrival = $journey->getArrivalAgency();

        if ($departure && $arrival && $departure === $arrival) {
            $this->context->buildViolation($constraint->message)
                ->atPath('arrivalAgency')
                ->addViolation();
        }
    }
}