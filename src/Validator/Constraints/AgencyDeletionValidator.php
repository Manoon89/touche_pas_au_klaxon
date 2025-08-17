<?php

namespace App\Validator\Constraints;

use App\Entity\Agency;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class AgencyDeletionValidator extends ConstraintValidator
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function validate($agency, Constraint $constraint)
    {
        if (!$agency instanceof Agency) {
            return;
        }

        $hasDeparture = count($agency->getDepartureJourneys()) > 0;
        $hasArrival = count($agency->getArrivalJourneys()) > 0;

        if ($hasDeparture || $hasArrival) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }

}