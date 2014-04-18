<?php
namespace ACS\ACSPanelBundle\Form;

use Craue\FormFlowBundle\Form\FormFlow;

class RegisterHostingFlow extends FormFlow {

    protected $maxSteps = 3;

    protected function loadStepDescriptions() {
        return array(
            'Basic',
            'Mail',
            'Databases',
        );
    }

    public function getName()
    {
        return 'register_hosting';
    }

}
