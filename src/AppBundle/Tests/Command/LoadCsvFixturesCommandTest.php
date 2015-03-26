<?php

namespace AppBundle\Tests\Command;

use AppBundle\Command\LoadCsvFixturesCommand;
use AppBundle\Tests\Controller\AbstractController;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Bundle\FrameworkBundle\Console\Application;

class LoadCsvFixturesCommandTest extends AbstractController
{
    public function testExecuteCommand()
    {
        $kernel = $this->createKernel();
        $kernel->boot();

        $application = new Application($kernel);
        $application->add(new LoadCsvFixturesCommand());

        $command = $application->find('app:load-csv-fixtures');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
                'entity'    => 'employee',
                'isTranslation'    => 'translation',
        ]
    );

        $this->assertContains("Load is finished!", $commandTester->getDisplay());
    }
}
