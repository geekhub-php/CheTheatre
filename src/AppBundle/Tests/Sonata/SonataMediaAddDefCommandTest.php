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

class SonataMediaAddDefCommandTest extends CommandTestCase
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
            'default',
            sprintf("%s/../web/uploads/media/media_bundle/images/employee_small", $baseFolder)
        ));
        $this->assertContains("Add a new media - context: default, provider: sonata.media.provider.image, content: ", $output);
        $this->assertContains("done!", $output);
    }
}
