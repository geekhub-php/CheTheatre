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

class SonataAdminListPostCommandTest extends CommandTestCase
{
    public function testListing()
    {
        $client = self::createClient();
        $output = $this->runCommand($client, "sonata:admin:list");
        $this->assertNotNull($output);
        foreach (self::getAdminList() as $def) {
            list($title) = $def;
            $this->assertContains($title, $output);
        }
    }
}