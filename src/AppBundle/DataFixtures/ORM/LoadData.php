<?php
namespace AppBundle\DataFixtures\ORM;

use Application\Sonata\MediaBundle\Entity\Media;
use Hautelook\AliceBundle\Alice\DataFixtureLoader;

class LoadData extends DataFixtureLoader
{
    protected function getFixtures()
    {
        return array(
            __DIR__.'/fixturesEmployee.yml',
            __DIR__.'/fixturesPerformance.yml',
            __DIR__.'/fixturesRole.yml',
            __DIR__.'/fixturesPerformanceEvent.yml',
            __DIR__.'/fixturesTag.yml',
            __DIR__.'/fixturesPost.yml',
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
