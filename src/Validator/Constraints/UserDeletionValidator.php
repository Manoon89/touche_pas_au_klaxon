<?php 

namespace App\Validator\Constraints;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class UserDeletionValidator extends ConstraintValidator
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function validate($user, Constraint $constraint)
    {
        if (!$user instanceof User) {
            return;
        }

        $hasJourneys = count($user->getJourneys()) > 0;

        if ($hasJourneys) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}