<?php
namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Hautelook\AliceBundle\Alice\DataFixtureLoader;
use Nelmio\Alice\Fixtures;

class LoadData extends DataFixtureLoader
{
    public function load(ObjectManager $om)
    {
        Fixtures::load(__DIR__ . '/fixtures.yml', $om, array('providers' => array($this)));
    }

    protected function getFixtures()
    {
        return array(
            __DIR__ . '/fixtures.yml',
        );
    }
}
