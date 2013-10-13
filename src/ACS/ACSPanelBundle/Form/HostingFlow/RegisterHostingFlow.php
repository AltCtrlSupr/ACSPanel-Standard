<?php
namespace ACS\ACSPanelBundle\Form\HostingFlow;

use Craue\FormFlowBundle\Form\FormFlow;

class RegisterHostingFlow extends FormFlow {

    /**
     * @var FormTypeInterface
     */
    protected $formType;

    public function setFormType($formType) {
        $this->formType = $formType;
    }

    protected function loadStepsConfig() {

        return array(
            array(
                'label' => 'hosting.flow.basic',
                'type' => $this->formType,
            ),
            array(
                'label' => 'hosting.flow.mail',
                'type' => $this->formType,
            ),
            array(
                'label' => 'hosting.flow.database',
            ),
        );
    }

    public function getName()
    {
        return 'register_hosting';
    }

}
