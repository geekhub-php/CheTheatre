<?php

namespace AppBundle\DataFixtures\ORM;

class FixturesLoader extends LoadData
{
    const FIXTURES_EXT = 'yml';

    /** @var array */
    private $fixturesPaths = [];

    /** @var  string */
    private $fixtureSourceDir;

    public function __construct($dir = null)
    {
        $dirName = empty($dir) ? '' : $dir.DIRECTORY_SEPARATOR;
        $this->fixtureSourceDir = __DIR__.DIRECTORY_SEPARATOR.$dirName;
    }

    /**
     * @param string $fixtureName
     *
     * @throws \Exception
     */
    public function addFixture(string $fixtureName)
    {
        $fixturePath = sprintf('%s%s.%s', $this->fixtureSourceDir, $fixtureName, self::FIXTURES_EXT);

        if (!file_exists($fixturePath)) {
            throw new \Exception("Fixture path not found: {$fixturePath}");
        }

        $this->fixturesPaths[] = $fixturePath;
    }

    /**
     * @inheritdoc
     */
    protected function getFixtures()
    {
        return $this->fixturesPaths;
    }
}
