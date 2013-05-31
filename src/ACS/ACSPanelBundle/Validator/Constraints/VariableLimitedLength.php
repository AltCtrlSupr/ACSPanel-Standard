<?php
namespace ACS\ACSPanelBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
/**
 * ConstrainsDuplicateHost
 *
 * @author genar
 */
class VariableLimitedLength extends Constraint
{

    public $max_length;

    public $message = 'The "%string%" is too long.';

    public function __construct($options = null)
    {
        parent::__construct($options);

        if('' == $this->max_length)
            throw new MissingOptionsException('The max_length value must be specified for ' . __CLASS__);
    }

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }

    public function validatedBy()
    {
        return 'db.username.validator';
    }
}

?>
