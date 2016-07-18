<?php

namespace ACS\ACSPanelBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class UserServiceManagerCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('acs:services:allow-use')
            ->setDescription('Allow user to use the selected service')
            ->addArgument(
                'username',
                InputArgument::OPTIONAL,
                'The name of the user to attach the service'
            )

        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
    }
}
