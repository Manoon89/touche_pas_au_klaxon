<?php 

namespace App\Validator\Constraints;

use App\Entity\User;
use App\Validator\Constraints\UserDeletion;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Valide qu'un utilisateur ne peut pas être supprimé s'il a des trajets associés
 */
class UserDeletionValidator extends ConstraintValidator
{

    /**
     * Vérifie si l'utilisateur a des trajets associés
     * 
     * @param $user
     * @param Constraint $contraint
     * 
     * @return void
     */
    public function validate($user, Constraint $constraint)
    {
        if (!$constraint instanceof UserDeletion) {
            return;
        }

        if (!$user instanceof User) {
            return;
        }

        $hasJourneys = count($user->getJourneys()) > 0;

        if ($hasJourneys) {
            $constraint = $constraint;
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}