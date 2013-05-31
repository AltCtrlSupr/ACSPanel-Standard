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
    public function validate($dbuser, Constraint $constraint)
    {
        // Check the limit length
        //$user_id = $container->get('security.context')->getToken()->getUser()->getId();
        $user_id = $dbuser->getDB()->getUser()->getId();

        $username = $dbuser;

        if(strlen($username) > 16){
            $this->context->addViolation($constraint->message, array('%string%' => $username));
        }
    }

}

?>
