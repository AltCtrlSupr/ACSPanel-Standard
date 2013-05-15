<?php
namespace ACS\ACSPanelBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
/**
 * ConstrainsDuplicateHost
 *
 * @author genar
 */
class DuplicateHost extends Constraint
{
    public $message = 'The host "%string%" already exists.';
	public $target_entity = '';
	public $target_field = '';

	public function __construct($options = null)
    {
        parent::__construct($options);

        if ('' == $this->target_entity && '' == $this->target_field ) {
            throw new MissingOptionsException('Both option "target_entity" and "target_field" must be given for constraint ' . __CLASS__, array('target_entity', 'target_field'));
        }
    }


	public function validatedBy()
    {
        return 'duplicate.host';
    }

}

?>
