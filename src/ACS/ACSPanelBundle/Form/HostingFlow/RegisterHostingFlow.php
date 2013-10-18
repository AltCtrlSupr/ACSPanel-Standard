<?php
namespace ACS\ACSPanelBundle\Form\HostingFlow;

use Craue\FormFlowBundle\Form\FormFlow;

class RegisterHostingFlow extends FormFlow {

    /**
     * @var FormTypeInterface
     */
    protected $formType;

    protected $container;

    protected $em;

    public function setFormType($formType) {
        $this->formType = $formType;
    }

    public function setContainer($container) {
        $this->container = $container;
    }

    public function setEm($em) {
        $this->em = $em;
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
            3 => array(
                'label' => 'hosting.flow.users',
                'type' => $this->formType,
            ),
        );
    }

    public function getFormOptions($step, array $options = array()) {
        $options = parent::getFormOptions($step, $options);

        $formData = $this->getFormData();

        $options['container'] = $this->container;

        $options['em'] = $this->em;


        if ($step === 2) {
            $options['domain'] = $formData->getDomains()->first();
        }

        return $options;
    }

    public function getName()
    {
        return 'register_hosting';
    }

}
