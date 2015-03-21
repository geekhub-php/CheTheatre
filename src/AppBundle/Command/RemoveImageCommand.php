<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\PropertyAccess\PropertyAccessor;

class RemoveImageCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('app:remove-image')
            ->setDescription('Remove image that not relation to media')
        ;
    }

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
            $entity = "AppBundle\\Entity\\".ucfirst($media->getContext());

            switch ($media->getContext()) {
                case 'employee':
                    $property = "avatar";
                    break;
                case 'performance':
                case 'post':
                    $property = "mainPicture";
            }

            $objects = $em
                        ->getRepository($entity)
                        ->findAll()
            ;

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

            if (count($objects) == $counter) {
                $message = sprintf('Remove media with id: %s and context: %s', $media->getId(), $media->getContext());
                $output->writeln($message);

                $mm->delete($media);
            }
        }

        $output->writeln('Delete media without reference object successful removed');
    }
}
