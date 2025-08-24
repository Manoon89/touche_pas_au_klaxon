<?php

namespace App\Validator\Constraints;

use App\Entity\Journey;
use App\Validator\Constraints\JourneyCities;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class JourneyCitiesValidator extends ConstraintValidator
{
    public function validate($journey, Constraint $constraint)
    {
        if (!$constraint instanceof JourneyCities) {
            return;
        }

        if (!$journey instanceof Journey) {
            return;
        }

        $departure = $journey->getDepartureAgency();        
        $arrival = $journey->getArrivalAgency();

        if ($departure && $arrival && $departure === $arrival) {
            $constraint = $constraint;
            $this->context->buildViolation($constraint->message)
                ->atPath('arrivalAgency')
                ->addViolation();
        }
    }
}