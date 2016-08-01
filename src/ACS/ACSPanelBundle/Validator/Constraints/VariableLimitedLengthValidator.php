<?php
/**
 * VariableLimitedLengthValidator
 *
 * @author genar
 */

namespace ACS\ACSPanelBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

//TODO: add more flexibility and more comprehensible validator names
class VariableLimitedLengthValidator extends ConstraintValidator
{
    public $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    public function validate($dbuser, Constraint $constraint)
    {
        $container = $this->container;
        // Check the limit length
        if (!$dbuser->getDB()->getUser()) {
            $user_id = $container->get('security.token_storage')->getToken()->getUser()->getId();
        } else {
            $user_id = $dbuser->getDB()->getUser()->getId();
        }

        $username = $dbuser;

        if(strlen($username) > 16){
            $this->context->addViolation($constraint->message, array('%string%' => $username));
        }
    }
}
