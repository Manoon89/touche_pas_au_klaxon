<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use App\Validator\Constraints\JourneySeatsValidator;

/**
 * Constraint pour empêcher le nombre de places disponibles d'être supérieur au nombre de places total du véhicule
 */
#[\Attribute]
class JourneySeats extends Constraint
{
    /**
     * Message affiché lorsqu'une violation de contrainte est détectée
     * 
     * @var string
     */
    public string $message = 'Le nombre de places disponibles ne peut pas être supérieur au nombre de places total';

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
     * Retourne le nom du service ou de la classe qui valide cette contrainte
     * 
     * @return string
     */    
    public function validatedBy(): string
    {
        return JourneySeatsValidator::class;
    }
}