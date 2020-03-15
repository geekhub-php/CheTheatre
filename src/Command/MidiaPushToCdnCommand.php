<?php

namespace App\Command;

use App\Entity\Media;
use Sonata\MediaBundle\Command\UpdateCdnStatusCommand;
use Sonata\MediaBundle\Provider\BaseProvider;
use Sonata\MediaBundle\Provider\MediaProviderInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\File\File;

class MidiaPushToCdnCommand extends Command implements ContainerAwareInterface
{
    protected static $defaultName = 'midia:push-to-cdn';

    private ContainerInterface $container;

    public function configure()
    {
        $this
            ->setDescription('Add a short description for your command')
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $imageProviderName = 'sonata.media.provider.image';
        $em = $this->container->get('doctrine.orm.entity_manager');
        $medias = $em
            ->getRepository(Media::class)
            ->findBy(['providerName' => $imageProviderName, 'description' => null]);
        $mediaPool = $this->container->get('sonata.media.pool');
        $fileProvider = $this->container->get($imageProviderName);
        $rootDir = $this->container->getParameter('kernel.project_dir');
        $imageProvider = $mediaPool->getProvider($imageProviderName);

        $io->progressStart(count($medias));

        /** @var Media $media */
        foreach ($medias as $media) {
            $filePath = sprintf('%s/public/uploads/%s', $rootDir, $imageProvider->getReferenceImage($media));

            $file = new File($filePath);
            $media->setBinaryContent($file);

            try {
                $fileProvider->postPersist($media);
            } catch (\Throwable $e) {
                $io->error(sprintf('Error "%s" while send media with %s id and "%s" path', $e->getMessage(), $media->getId(), $filePath));
                $io->comment($e->getTraceAsString());
                return 1;
            }

            $media->setDescription('');
            $em->flush();

            $io->progressAdvance();
        }

        $io->progressFinish();
        $io->success('Done');

        return 0;
    }

    /**
     * @inheritDoc
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
}
