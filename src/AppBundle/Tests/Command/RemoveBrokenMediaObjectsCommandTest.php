<?php

namespace AppBundle\Tests\Command;

use AppBundle\Command\RemoveBrokenMediaObjectsCommand;
use AppBundle\Tests\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Bundle\SecurityBundle\Tests\Functional\WebTestCase;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Application\Sonata\MediaBundle\Entity\Media;

class RemoveImageCommandTest extends AbstractController
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

        $this->assertContains("Delete media without reference object successful removed", $commandTester->getDisplay());

        $this->assertNotContains("Remove media with", $commandTester->getDisplay());

        $media = new Media();

        $media->setBinaryContent(__DIR__.'/../../DataFixtures/data/avatars/anna-bobrova.jpg');
        $media->setContext('employee');
        $media->setProviderName('sonata.media.provider.image');

        $this->getContainer()->get('sonata.media.manager.media')->save($media, $andFlush = true);

        $this->getEm()->persist($media);
        $this->getEm()->flush();

        $commandTester->execute([]);
        $this->assertContains("employee", $commandTester->getDisplay());
    }
}
