<?php

namespace AppBundle\Tests\Command;

use AppBundle\Command\RemoveImageCommand;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Bundle\FrameworkBundle\Console\Application;

class RemoveImageCommandTest extends KernelTestCase
{
    public function testExecuteCommand()
    {
        $kernel = $this->createKernel();
        $kernel->boot();

        $application = new Application($kernel);
        $application->add(new RemoveImageCommand());

        $command = $application->find('app:remove-image');
        $commandTester = new CommandTester($command);
        $commandTester->execute([]);

        $this->assertContains("Delete media without reference object successful removed", $commandTester->getDisplay());

    }
}