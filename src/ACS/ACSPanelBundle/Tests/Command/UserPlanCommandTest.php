<?php
namespace ACS\ACSPanelBundle\Tests\Command;

use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Bundle\FrameworkBundle\Console\Application;

use ACS\ACSPanelBundle\Command\UserPlanCommand;
use ACS\ACSPanelBundle\Tests\Controller\CommonTestCase;

class UserPlanCommandTest extends CommonTestCase
{
    public function testExecute()
    {
        $kernel = $this->createKernel();
        $kernel->boot();

        $application = new Application($kernel);
        $application->add(new UserPlanCommand());

        $command = $application->find('acs:add-plan-to-user');

        // Tests for Plan entity
        $commandTester = new CommandTester($command);
        $commandTester->execute(
            array(
                'plan' => '1',
                'username' => 'superadmin'
            )
        );

        // This should add User only for admins
        $this->assertRegExp('/Added/', $commandTester->getDisplay());
    }
}
