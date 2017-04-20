<?php

namespace AppBundle\Command;

use AppBundle\Entity\PerformanceEvent;
use AppBundle\Entity\Ticket;
use AppBundle\Exception\Ticket\DuplicateSetException;
use AppBundle\Repository\PerformanceEventRepository;
use AppBundle\Repository\TicketRepository;
use AppBundle\Services\Ticket\GenerateSetHandler;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Security\Core\Authentication\Token\AnonymousToken;

class GenerateTicketsCommand extends ContainerAwareCommand
{
    /** @var GenerateSetHandler */
    private $ticketGenerateSet;

    /** @var PerformanceEventRepository */
    private $performanceEventRepository;

    /** @var TicketRepository */
    private $ticketRepository;

    public function __construct(
        GenerateSetHandler $ticketGenerateSet,
        PerformanceEventRepository $performanceEventRepository,
        TicketRepository $ticketRepository
    ) {
        parent::__construct();
        $this->ticketGenerateSet = $ticketGenerateSet;
        $this->performanceEventRepository = $performanceEventRepository;
        $this->ticketRepository = $ticketRepository;
    }

    protected function configure()
    {
        $this
            ->setName('app:generate-tickets')
            ->setDescription('Generate new Set of Tickets for performanceEvent')
            ->addArgument(
                'performanceEventId',
                InputArgument::REQUIRED,
                'The Performance Event ID.'
            )
            ->addOption(
                'force',
                'f',
                InputOption::VALUE_OPTIONAL,
                'Remove previously generated tickets set for PerformanceEvent and generates new one.',
                false
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $startTime = new \DateTime('now');

            $token = new AnonymousToken('console_user', 'console_user', ['ROLE_SUPER_ADMIN']);
            $this->getContainer()->get('security.token_storage')->setToken($token);

            $output->writeln('<comment>Running Tickets Generation</comment>');
            $performanceEventId = (int) $input->getArgument('performanceEventId') ?: null;
            $force = (bool) $input->getOption('force')  ? true : false;

            /** @var PerformanceEvent $performanceEvent */
            $performanceEvent = $this->performanceEventRepository->getById($performanceEventId);

            if ($force) {
                $tickets = $this->ticketRepository->getRemovableTicketSet($performanceEvent);
                $this->ticketRepository->batchRemove($tickets);
            }

            if ($this->ticketRepository->isGeneratedSet($performanceEvent)) {
                throw new DuplicateSetException('Ticket Set already generated for: '. $performanceEvent);
            }

            /** @var Ticket[] $tickets */
            $tickets = $this->ticketGenerateSet->handle($performanceEvent);
            $this->ticketRepository->batchSave($tickets);
            $output->writeln(sprintf('<info>SUCCESS. %s tickets were generated</info>', count($tickets)));
        } catch (\Exception $e) {
            $output->writeln('<error>ERROR Generating Tickets: '.$e->getMessage().'</error>');
        } finally {
            $finishTime = new \DateTime('now');
            $interval = $startTime->diff($finishTime);
            $output->writeln(sprintf('<comment>DONE in %s seconds<comment>', $interval->s));
        }
    }
}
