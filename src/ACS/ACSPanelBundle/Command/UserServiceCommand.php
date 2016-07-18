<?php

namespace ACS\ACSPanelBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Symfony\Component\Security\Acl\Permission\MaskBuilder;

class UserServiceCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('acs:add-service-to-user')
            ->setDescription('Update ACL entries based on current entity permissions')
            ->addArgument(
                'service',
                InputArgument::OPTIONAL,
                'Service name'
            )
            ->addArgument(
                'username',
                InputArgument::OPTIONAL,
                'Username'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $service_name = $input->getArgument('service');
        $username = $input->getArgument('username');

        $serviceManager = $this->getContainer()->get('service_manager');
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');

        $service = $em->getRepository('\ACS\ACSPanelBundle\Entity\Service')->findOneBy(array('name' => $service_name));
        $user = $em->getRepository('\ACS\ACSPanelUsersBundle\Entity\User')->findOneBy(array('username' => $username));

        if ($serviceManager->attachToUser($service, $user)) {
            $output->writeln("Added " . $service . " (Service) Acls for " . $user);
        }
    }
}
