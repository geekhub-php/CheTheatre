<?php
namespace AppBundle\DataFixtures\ORM;

use Application\Sonata\MediaBundle\Entity\Media;
use Hautelook\AliceBundle\Alice\DataFixtureLoader;

class LoadData extends DataFixtureLoader
{
    protected function getFixtures()
    {
        return array(
            __DIR__.'/fixturesEmployee_ua.yml',
            __DIR__.'/fixturesPerformance_ua.yml',
            __DIR__.'/fixturesRole_ua.yml',
            __DIR__.'/fixturesPerformanceEvent.yml',
            __DIR__.'/fixturesTag_ua.yml',
            __DIR__.'/fixturesPost_ua.yml',
            __DIR__.'/fixturesEmployee_en.yml',
//            __DIR__.'/fixturesPerformance_en.yml',
//            __DIR__.'/fixturesRole_en.yml',
            __DIR__.'/fixturesTag_en.yml',
            __DIR__.'/fixturesPost_en.yml',
        );
    }

    public function getMedia($name, $context = 'default')
    {
        $avatar = new Media();

        $avatar->setBinaryContent(__DIR__.'/../data/'.$name);
        $avatar->setContext($context);
        $avatar->setProviderName('sonata.media.provider.image');

        $this->container->get('sonata.media.manager.media')->save($avatar, $andFlush = true);

        return $avatar;
    }
}
