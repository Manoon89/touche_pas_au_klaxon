<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use App\Validator\Constraints\JourneyCitiesValidator;

/**
 * Constraint pour empêcher que la ville de départ et d'arrivée d'un trajet soient identiques
 */
#[\Attribute]
class JourneyCities extends Constraint
{
    /**
     * Message affiché lorsqu'une violation de contrainte est détectée
     * 
     * @var string
     */
    public string $message = 'La ville de départ et la ville d’arrivée doivent être différentes.';

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
        return JourneyCitiesValidator::class;
    }
}