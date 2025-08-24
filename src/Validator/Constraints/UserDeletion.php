<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * Constraint pour empêcher la suppression ou la modification d'un utilisateur s'il a créé un ou plusieurs trajets
 */
#[\Attribute]
class UserDeletion extends Constraint
{
    /**
     * Message affiché lorsqu'une violation de contrainte est détectée
     * 
     * @var string
     */
    public $message = 'Impossible de modifier ou supprimer cet utilisateur : il est associé à un ou plusieurs trajets';

    /**
     * Définit la cible de la contrainte. Ici elle s'applique sur toute la classe. 
     * 
     * @return string
     */
    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
}