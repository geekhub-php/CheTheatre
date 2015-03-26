<?php

namespace AppBundle\Tests\Command;

use AppBundle\Command\RemoveBrokenMediaObjectsCommand;
use AppBundle\Tests\Controller\AbstractController;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Application\Sonata\MediaBundle\Entity\Media;

class RemoveBrokenMediaObjectsCommandTest extends AbstractController
{
    public function testExecuteCommand()
    {
        $kernel = $this->createKernel();
        $kernel->boot();

        $application = new Application($kernel);
        $application->add(new RemoveBrokenMediaObjectsCommand());

        $command = $application->find('app:remove-image');
        $commandTester = new CommandTester($command);
        $commandTester->execute([]);

        $this->assertContains("Deleted media without reference object was successful", $commandTester->getDisplay());

        $commandTester->execute([]);

        $this->assertNotContains("Remove media with", $commandTester->getDisplay());

        $media = new Media();

        $media->setBinaryContent(__DIR__.'/../../DataFixtures/data/avatars/anna-bobrova.jpg');
        $media->setContext('employee');
        $media->setProviderName('sonata.media.provider.image');

        $this->getContainer()->get('sonata.media.manager.media')->save($media, $andFlush = true);

        $this->getEm()->persist($media);
        $this->getEm()->flush();

        $commandTester->execute([]);
        $this->assertContains("Removed media with id", $commandTester->getDisplay());
    }
}
