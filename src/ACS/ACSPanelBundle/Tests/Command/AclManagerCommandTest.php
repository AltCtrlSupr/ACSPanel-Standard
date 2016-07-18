<?php
namespace ACS\ACSPanelBundle\Tests\Command;

use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Bundle\FrameworkBundle\Console\Application;

use ACS\ACSPanelBundle\Command\AclManagerCommand;
use ACS\ACSPanelBundle\Tests\Controller\CommonTestCase;

class AclManagerCommandTest extends CommonTestCase
{
    public function testExecute()
    {
        $kernel = $this->createKernel();
        $kernel->boot();

        $application = new Application($kernel);
        $application->add(new AclManagerCommand());

        $command = $application->find('acl-manager:update-entity');

        // Tests for Plan entity
        $commandTester = new CommandTester($command);
        $commandTester->execute(
            array(
                'entity' => '\ACS\ACSPanelBundle\Entity\Plan'
            )
        );

        // This should add User only for admins
        $this->assertRegExp('/Added/', $commandTester->getDisplay());

        // Tests for User entity
        $commandTester->execute(
            array(
                'entity' => '\ACS\ACSPanelUsersBundle\Entity\User'
            )
        );
        $this->assertRegExp('/Added/', $commandTester->getDisplay());

        // Tests for MailMailbox entity
        $commandTester->execute(
            array(
                'entity' => '\ACS\ACSPanelBundle\Entity\MailMailbox'
            )
        );
        // this works but we need data to handle the assert
        // $this->assertRegExp('/Added/', $commandTester->getDisplay());

        // Tests for MailAlias entity
        $commandTester->execute(
            array(
                'entity' => '\ACS\ACSPanelBundle\Entity\MailAlias'
            )
        );
        // this works but we need data to handle the assert
        // $this->assertRegExp('/Added/', $commandTester->getDisplay());
    }
}
