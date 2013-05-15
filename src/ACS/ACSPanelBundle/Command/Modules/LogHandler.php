<?php

namespace ACS\ACSPanelBundle\Modules;

use Symfony\Component\DependencyInjection\Container;

use Doctrine\ORM\EntityManager;

use ACS\ACSPanelBundle\Entity\LogItem;
use Monolog\Logger;

class LogHandler 
{
    private $initialized = false;
	private $container = null;
	private $module = '';
	private $action = '';
	private $object_reference = '';

    public function __construct(Container $container, $level = Logger::DEBUG, $bubble = true){
        $this->container = $container;
    }

    public function setModule($module)
    {
        $this->module = $module;
    }

    public function setAction($action)
    {
        $this->action = $action;
    }

    public function setObjectReference($or)
    {
        $this->object_reference = $or;
    }

    public function write($message, $reference = '' ){
        if (!$this->initialized) {
            $this->initialize();
        }

        $logitem = new LogItem();        
        $logitem->setMessage($message);

		// If there's no reference we look for id in request
		if($reference == '' && $this->container->hasParameter('id'))
			$reference = $this->container->getParameter('id');

        $logitem->setObjectReference($reference);

		$request = $this->container->get('request');
		$routeName = $request->get('_route');
        $logitem->setRoute($routeName);

		$em = $this->container->get('doctrine.orm.entity_manager');
        $em->persist($logitem);
        $em->flush();
    }

    private function initialize(){
        $this->initialized = true;
    }
}