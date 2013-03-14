<?php
namespace ACS\ACSPanelBundle\Listener;

use ACS\ACSPanelBundle\Controller\LoggableController;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpKernel\Kernel;

/**
 * LoggerListener
 *
 * @author genar
 */
class LoggerListener
{
    private $container = null;
    private $kernel;

    public function __construct(Container $container, Kernel $kernel)
    {
        $this->container = $container;
        $this->kernel = $kernel;
    }

    /**
     * Is not called anymore, just for tests
     * @deprecated
     * @param FilterControllerEvent $event
     * @return type
     */
    public function onKernelController(FilterControllerEvent $event)
    {
        $controller = $event->getController();

        /*
         * $controller passed can be either a class or a Closure. This is not usual in Symfony2 but it may happen.
         * If it is a class, it comes in array format
         */
        if (!is_array($controller)) {
            return;
        }

        if ($controller[0] instanceof LoggableController) {

            $request = $controller[0]->getRequest();

            $action_name = $this->getActionName($request);

            $session = $request->getSession();

            $log_message = '';
            $log_reference = '';

            if($session->get('logger_message'))
                $log_message = $session->get('logger_message');
            else
                $log_message = print_r($request->get('_route_params'),1);

            if($session->get('logger_reference'))
                $log_reference = $session->get('logger_reference');
            else
                $log_reference = print_r($request->get('id'),1);

            if($action_name == 'update' || $action_name == 'create' || $action_name == 'delete')
                $this->container->get('userlog.system')->write($log_message, $log_reference);
        }
    }

    public function onKernelResponse(FilterResponseEvent $event)
    {
        return;
    }

    public function getControllerName($request_attributes){
        $pattern = "/Controller\\\\([a-zA-Z]*)Controller/";
        $matches = array();
        preg_match($pattern, $request_attributes->get("_controller"), $matches);

        return $matches[1];
    }

    public function getActionName($request_attributes){

        $pattern = "/Controller\\::([a-zA-Z]*)Action/";
        $matches = array();
        preg_match($pattern, $request_attributes->get("_controller"), $matches);
        return $matches[1];
    }

}

?>
