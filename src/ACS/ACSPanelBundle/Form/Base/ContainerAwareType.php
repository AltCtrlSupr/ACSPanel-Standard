<?php

namespace ACS\ACSPanelBundle\Form\Base;

use Symfony\Component\Form\AbstractType;

abstract class ContainerAwareType extends AbstractType
{
    public function __construct($container)
    {
        $this->container = $container;
    }
}
