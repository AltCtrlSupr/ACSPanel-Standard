<?php
namespace ACS\ACSPanelBundle\Tests\Command;

use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Bundle\FrameworkBundle\Console\Application;

use ACS\ACSPanelBundle\Command\UserServiceCommand;
use ACS\ACSPanelBundle\Tests\Controller\CommonTestCase;

class UserServiceCommandTest extends CommonTestCase
{
    public function testExecute()
    {
        $kernel = $this->createKernel();
        $kernel->boot();

        $application = new Application($kernel);
        $application->add(new UserServiceCommand());

        $command = $application->find('acs:add-service-to-user');

        // Tests for Plan entity
        $commandTester = new CommandTester($command);
        $commandTester->execute(
            array(
                'service' => 'web.acs.li',
                'username' => 'superadmin'
            )
        );

        // This should add User only for admins
        $this->assertRegExp('/Added/', $commandTester->getDisplay());
    }
}
