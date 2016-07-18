<?php
namespace ACS\ACSPanelSettingsBundle\Tests\Command;

use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

use ACS\ACSPanelSettingsBundle\Command\CheckSettingsCommand;

class CheckSettingsCommandTest extends KernelTestCase
{
    public function testExecute()
    {
        $kernel = $this->createKernel();
        $kernel->boot();

        $application = new Application($kernel);
        $application->add(new CheckSettingsCommand());

        $command = $application->find('acssettings:check-and-dump');

        $commandTester = new CommandTester($command);

        // CAUTION WITH THIS!! Very slow process
        /* V$commandTester->execute(
            array(
                // 'param' => 'value',
            )
        ); */

        // This should add User only for admins
        // $this->assertRegExp('/Added watcher for /', $commandTester->getDisplay());
    }
}
