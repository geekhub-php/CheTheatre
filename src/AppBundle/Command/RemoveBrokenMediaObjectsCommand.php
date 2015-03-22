<?php

namespace AppBundle\Command;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\PropertyAccess\PropertyAccessor;

class RemoveBrokenMediaObjectsCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('app:remove-image')
            ->setDescription('Remove image that not relation to media')
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $mm = $this
            ->getContainer()
            ->get('sonata.media.manager.media')
        ;

        $em = $this
            ->getContainer()
            ->get('doctrine')
            ->getManager()
        ;

        $medias = $mm->findAll();

        foreach ($medias as $media) {
            $context = $media->getContext() == "slider" ? "performance" : $media->getContext();
            $entity = "AppBundle\\Entity\\".ucfirst($context);

            $objects = $em
                ->getRepository($entity)
                ->findAll()
            ;

            $counter = $this->proccessMediaObject($media, $objects);

            $this->removeMedia($output, $mm, $media, count($objects), $counter);
        }

        $output->writeln('Delete media without reference object successful removed');
    }

    /**
     * @param $media
     * @return string
     */
    protected function getContext($media)
    {
        switch ($media) {
            case 'employee':
                $property = "avatar";
                break;
            case 'performance':
            case 'post':
                $property = "mainPicture";
                break;
            case 'slider':
                $property = "sliderImage";
                break;
        }

        return $property;
    }

    /**
     * @param $media
     * @param $objects
     * @return int
     */
    protected function proccessMediaObject($media, $objects)
    {
        $property = $this->getContext($media->getContext());

        $propertyAccessor = new PropertyAccessor();
        $counter = 0;

        foreach ($objects as $object) {
            $value = $propertyAccessor->getValue($object, $property);

            if (!is_null($value) && $value->getId() == $media->getId()) {
                break;
            } else {
                $counter++;
            }
        }

        return $counter;
    }

    protected function removeMedia(OutputInterface $output, $mm, $media, $objectCount, $counter)
    {
        if ($objectCount == $counter) {
            $message = sprintf('Remove media with id: %s and context: %s', $media->getId(), $media->getContext());
            $output->writeln($message);

            $mm->delete($media);
        }
    }
}
