<?php

namespace AppBundle\Command;

use AppBundle\Entity\PerformanceEvent;
use AppBundle\Repository\PerformanceEventRepository;
use AppBundle\Repository\TicketRepository;
use AppBundle\Services\Ticket\GenerateSetHandler;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateTicketsCommand extends ContainerAwareCommand
{

    /** @var  GenerateSetHandler */
    private $ticketGenerateSet;

    /** @var  PerformanceEventRepository */
    private $performanceEventRepository;

    /** @var  TicketRepository */
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
            $output->writeln('<comment>Running Tickets Generation</comment>');
            $performanceEventId = (int) $input->getArgument('performanceEventId') ?: null;
            $force = (bool) $input->getOption('force')  ? true : false;
            $performanceEvent = $this->performanceEventRepository->getById($performanceEventId);

            if ($force) {
                //TODO remove existed tickets for PerformanceEvent if they exists
            }

            if ($this->ticketsExists($performanceEvent)) {
                throw new \Exception('Tickets already exist for: '. $performanceEvent);
            }

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

   /**
     * @param PerformanceEvent $performanceEvent
     *
     * @return bool
     */
    protected function ticketsExists(PerformanceEvent $performanceEvent)
    {
        return (bool) count($this->ticketRepository->findBy([
            'performanceEvent' => $performanceEvent
        ]));
    }
}
