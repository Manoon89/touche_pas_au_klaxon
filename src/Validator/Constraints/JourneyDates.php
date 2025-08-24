<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use App\Validator\Constraints\JourneyDatesValidator;

/**
 * Constraint pour obliger la date d'arrivée à être supérieur à la date de départ ; 
 * et pour obliger la date de départ à être supérieure ou égale à la date du jour. 
 */
#[\Attribute]

class JourneyDates extends Constraint
{
    /**
     * Messages affichés lorsqu'une violation de contrainte est détectée
     * 
     * @var string
     */
    public $departureMessage = "La date de départ doit être supérieure ou égale à aujourd'hui";
    public $arrivalMessage = "La date d'arrivée doit être supérieure à la date de départ";

    /**
     * Définit la cible de la contrainte. Ici elle s'applique sur toute la classe. 
     * 
     * @return string
     */
    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }

    /**
     * Retourne le nom du service ou de la classe qui valide cette contrainte.
     *
     * @return string 
     */
    public function validatedBy(): string
    {
        return JourneyDatesValidator::class;
    }

}