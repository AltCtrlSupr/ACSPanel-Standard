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
            1 => array(
                'label' => 'hosting.flow.basic',
                'type' => $this->formType,
            ),
            2 => array(
                'label' => 'hosting.flow.dns',
                'type' => $this->formType,
            ),
            2 => array(
                'label' => 'hosting.flow.users',
                'type' => $this->formType,
            ),
        );
    }

    public function getFormOptions($step, array $options = array()) {
        $options = parent::getFormOptions($step, $options);

        $formData = $this->getFormData();

        /*if ($step === 2) {
            $options['domain'] = $formData->getDomain();
        }*/

        return $options;
    }

    public function getName()
    {
        return 'register_hosting';
    }

}
