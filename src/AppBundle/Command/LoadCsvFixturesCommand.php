<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Yaml;

class LoadCsvFixturesCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('app:load-csv-fixtures')
            ->setDescription('Load fixtures to yml from csv files')
            ->addArgument(
                'entity',
                InputArgument::REQUIRED,
                'The name of entity for which the fixtures are loaded.'
            )
            ->addArgument(
                'isTranslation',
                InputArgument::OPTIONAL,
                'Add "translation" parameter if you are loading translations for this entity.'
            );
    }

    /**
     * @param  InputInterface  $input
     * @param  OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $entityName = $input->getArgument('isTranslation') ?
            $input->getArgument('entity').'Translation' :
            $input->getArgument('entity')
        ;

        $fileName = $input->getArgument('isTranslation') ?
            'fixtures'.ucfirst($entityName).'_en' :
            'fixtures'.ucfirst($entityName).'_uk'
        ;

        $csvDir = __DIR__.'/../DataFixtures/data/csv/';
        $csvFile = $csvDir.$fileName.'.csv';

        $ymlDir = __DIR__.'/../DataFixtures/ORM/';
        $ymlFile = $ymlDir.$fileName.'.yml';

        if ($yamlArray = $this->csvToArray($entityName, $csvFile)) {
            $yaml = Yaml::dump($yamlArray, 3);
            $yaml = str_replace('\'<', '<', $yaml);
            $yaml = str_replace('>\'', '>', $yaml);
            $yaml = str_replace('\'[', '[', $yaml);
            $yaml = str_replace(']\'', ']', $yaml);

            file_put_contents($ymlFile, $yaml);

            $output->writeln('Load is finished!');
        } else {
            $output->writeln('Load is failed!');
        }
    }

    protected function csvToArray($entityName, $csvFile)
    {
        if (($handle = fopen($csvFile, 'r')) !== false) {
            $allData = [];

            while (($data = fgetcsv($handle)) !== false) {
                $allData[] = $data;
            }

            fclose($handle);

            $entityPath = $allData[0][0];
            $yamlArray = [];
            $number = count($allData[0]);

            for ($i = 2; $i < (count($allData)); $i++) {
                for ($c = 0; $c < $number; $c++) {
                    $yamlArray[$entityPath][$entityName.($i - 1)][$allData[1][$c]] = $allData[$i][$c];
                }
            }

            return $yamlArray;
        } else {
            return;
        }
    }
}
