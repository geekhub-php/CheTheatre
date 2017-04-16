<?php
/*
* This file is part of the Sonata package.
*
* (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace AppBundle\Tests\Sonata;

class SonataMediaAddEmployeeCommandTest extends CommandTestCase
{
    /**
     * @expectedException \RuntimeException
     */
    public function testException()
    {
        $client = self::createClient();
        $this->runCommand($client, "sonata:media:add");
    }

    public function testMediaAdd()
    {
        $client = self::createClient();
        $baseFolder = $client->getContainer()->getParameter('kernel.root_dir');
        $output = $this->runCommand($client, sprintf("sonata:media:add %s %s %s",
            'sonata.media.provider.image',
            'employee',
            sprintf("%s/../src/AppBundle/DataFixtures/data/avatars/vladimir-osipov.jpg", $baseFolder)
        ));
        $this->assertContains("Add a new media - context: employee, provider: sonata.media.provider.image, content: ", $output);
        $this->assertContains("done!", $output);
    }
}
