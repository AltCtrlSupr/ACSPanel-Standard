<?php
/**
 * ConstrainsDuplicateHost
 *
 * @author genar
 */

namespace ACS\ACSPanelBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

use Doctrine\ORM\EntityManager;

class DuplicateHostValidator extends ConstraintValidator
{
    private $em;

    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
    }

    public function validate($value, Constraint $constraint)
    {
        // Check for the hostname in httpdhosts and httpdalias
        $em = $this->em;
        $duplicates_found = $em->getRepository($constraint->target_entity)->findBy(array($constraint->target_field => $value));
        //print_r(count($duplicates_found));
        //exit;
        if(count($duplicates_found)){
            $this->context->addViolation($constraint->message, array('%string%' => $value));
        }

        return true;
    }
}

?>
