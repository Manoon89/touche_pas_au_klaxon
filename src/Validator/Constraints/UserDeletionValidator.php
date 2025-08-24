<?php 

namespace App\Validator\Constraints;

use App\Entity\User;
use App\Validator\Constraints\UserDeletion;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class UserDeletionValidator extends ConstraintValidator
{

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