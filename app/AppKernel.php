<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Symfony\Bundle\AsseticBundle\AsseticBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            new Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle(),
            new Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle(),
            new Knp\Bundle\MenuBundle\KnpMenuBundle(),
            new FOS\UserBundle\FOSUserBundle(),
            new Liip\ThemeBundle\LiipThemeBundle(),
            new Avanzu\AdminThemeBundle\AvanzuAdminThemeBundle(),
            new Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle(),
            new Braincrafted\Bundle\BootstrapBundle\BraincraftedBootstrapBundle(),
            new FOS\RestBundle\FOSRestBundle(),
            new Nelmio\ApiDocBundle\NelmioApiDocBundle(),
            new JMS\SerializerBundle\JMSSerializerBundle(),
            new Knp\Bundle\PaginatorBundle\KnpPaginatorBundle(),
            new Craue\FormFlowBundle\CraueFormFlowBundle(),
            new ACS\ACSPanelBundle\ACSACSPanelBundle(),
            new ACS\ACSPanelUsersBundle\ACSACSPanelUsersBundle(),
            new ACS\ACSPanelSettingsBundle\ACSACSPanelSettingsBundle(),
            new ACS\ACSPanelWordpressBundle\ACSACSPanelWordpressBundle(),
            new ACS\ACSPanelAnsibleBundle\ACSACSPanelAnsibleBundle(),
        );

        if (in_array($this->getEnvironment(), array('dev', 'test'))) {
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
            $bundles[] = new Liip\FunctionalTestBundle\LiipFunctionalTestBundle();
        }

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config/config_'.$this->getEnvironment().'.yml');
    }

    public function registerRootDir()
    {
        return __DIR__;
    }

    protected function initializeContainer() {
        parent::initializeContainer();
        if (PHP_SAPI == 'cli') {
            $this->getContainer()->enterScope('request');
            $this->getContainer()->set('request', new \Symfony\Component\HttpFoundation\Request(), 'request');
        }
    }
}
