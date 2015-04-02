<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use Sonata\MediaBundle\Entity\BaseMedia as SonataEntityMedia;

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
     * @param  InputInterface  $input
     * @param  OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $mediaManager = $this->getContainer()->get('sonata.media.manager.media');

        $associatedMediaMappings = $this->getAssociationMediaMappings();

        $associatedMediaObjects = $this->getAssociatedMediaObjects($associatedMediaMappings);
        $associatedMediaObjectsIds = array_map(function (SonataEntityMedia $media) {
            return $media->getId();
        }, $associatedMediaObjects);

        foreach ($mediaManager->findAll() as $media) {
            if (false == in_array($media->getId(), $associatedMediaObjectsIds)) {
                $output->writeln(sprintf('Removed media with id "%s"', $media->getId()));
                $mediaManager->delete($media);
            }
        }

        $output->writeln('Deleted media without reference object was successful');
    }

    /**
     * @param  array $associationMappings
     * @return array
     */
    protected function getAssociatedMediaObjects(array $associationMappings)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();

        $accessor = new PropertyAccessor();

        $mediaObjects = [];

        foreach ($associationMappings as $repositoryName => $properties) {
            foreach ($properties as $property) {
                $objects = $em->getRepository($repositoryName)->findAll();

                foreach ($objects as $object) {
                    if (!is_null($accessor->getValue($object, $property))) {
                        $mediaObjects[] = $accessor->getValue($object, $property);
                    }
                }
            }
        }

        return $mediaObjects;
    }

    /**
     * @return array associationMappings[className][propertyName][metadata]
     */
    protected function getAssociationMediaMappings()
    {
        $appClassMetadata = $this
            ->getContainer()
            ->get('sonata.media.manager.media')
            ->getEntityManager()
            ->getMetadataFactory()
            ->getAllMetadata()
        ;

        $mediaAssociationMappings = [];

        foreach ($appClassMetadata as $classMetadata) {
            foreach ($classMetadata->associationMappings as $propertyName => $associationMapping) {
                if ('Application\Sonata\MediaBundle\Entity\Media' == $associationMapping['targetEntity']) {
                    $mediaAssociationMappings[$classMetadata->name][] = $propertyName;
                }
            }
        }

        return $mediaAssociationMappings;
    }
}
