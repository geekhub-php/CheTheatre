<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RemoveImageCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('app:remove-image')
            ->setDescription('Remove image that not relation to media');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $mm = $this
            ->getContainer()
            ->get('sonata.media.manager.media');

        $em = $this
            ->getContainer()
            ->get('doctrine')
            ->getManager();

        $medias = $mm->findAll();

        foreach ($medias as $media){
            $entity = "AppBundle\\Entity\\".ucfirst($media->getContext)."s";

            switch ($media->getContext) {
                case 'employee':
                    $property = "avatar_id";
                    break;
                case 'perfomance':
                case 'post':
                    $property = "mainPicture_id";
            }

            $object = $em->geRepository($entity)
                         ->findOneBy([$property => $media->getId()]);

            if (!$object) {
                $provider = $this->getContainer($media->getProviderName());
                $provider->removeThumbnails($media);
                $mm->delete($media);
            }
        }
    }
}
