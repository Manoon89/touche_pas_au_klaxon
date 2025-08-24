<?php

namespace App\Validator\Constraints;

use App\Entity\Journey;
use App\Validator\Constraints\JourneyCities;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Valide qu'un trajet ne peut pas avoir la même agence de départ et d'arrivée
 */
class JourneyCitiesValidator extends ConstraintValidator
{
    /**
     * Vérifie que le trajet a des agences de départ et d'arrivée différentes
     * 
     * @param $journey
     * @param Constraint $contraint
     * 
     * @return void
     */
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