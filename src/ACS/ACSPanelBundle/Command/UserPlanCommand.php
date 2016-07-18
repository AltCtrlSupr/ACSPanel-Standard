<?php

namespace ACS\ACSPanelBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Symfony\Component\Security\Acl\Permission\MaskBuilder;

class UserPlanCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('acs:add-plan-to-user')
            ->setDescription('Add plan to user')
            ->addArgument(
                'plan',
                InputArgument::OPTIONAL,
                'Plan id'
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
        $plan = $input->getArgument('plan');
        $username = $input->getArgument('username');

        $planManager = $this->getContainer()->get('plan_manager');
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');

        $plan = $em->getRepository('\ACS\ACSPanelBundle\Entity\Plan')->findOneBy(array('id' => $plan));
        $user = $em->getRepository('\ACS\ACSPanelUsersBundle\Entity\User')->findOneBy(array('username' => $username));

        if ($planManager->attachToUser($plan, $user)) {
            $output->writeln("Added " . $plan. " (Plan) to  " . $user);
        }
    }
}
